<?php
/*
 * This file is a part of "furqansiddiqui/ethereum-php" package.
 * https://github.com/furqansiddiqui/ethereum-php
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/ethereum-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace FurqanSiddiqui\Ethereum\Contracts;

use Comely\DataTypes\Buffer\Base16;
use Comely\DataTypes\Strings\ASCII;
use FurqanSiddiqui\Ethereum\Contracts\ABI\Method;
use FurqanSiddiqui\Ethereum\Contracts\ABI\MethodParam;
use FurqanSiddiqui\Ethereum\Exception\ContractABIException;
use FurqanSiddiqui\Ethereum\Math\Integers;
use FurqanSiddiqui\Ethereum\Packages\Keccak\Keccak;

/**
 * Class ABI
 * https://github.com/ethereum/wiki/wiki/Ethereum-Contract-ABI
 * @package FurqanSiddiqui\Ethereum\Contracts
 */
class ABI
{
    /** @var null|Method */
    private ?Method $constructor = null;
    /** @var null|Method */
    private ?Method $fallback = null;
    /** @var array */
    private array $functions;
    /** @var array */
    private array $events;
    /** @var bool */
    private bool $strictMode;

    /**
     * ABI constructor.
     * @param array $abi
     */
    public function __construct(array $abi)
    {
        $this->strictMode = true;
        $this->functions = [];
        $this->events = [];

        $index = 0;
        foreach ($abi as $block) {
            try {
                if (!is_array($block)) {
                    throw new ContractABIException(
                        sprintf('Unexpected data type "%s" at ABI array index %d, expecting Array', gettype($block), $index)
                    );
                }

                $type = $block["type"] ?? null;
                switch ($type) {
                    case "constructor":
                    case "function":
                    case "fallback":
                        $method = new Method($block);
                        switch ($method->type) {
                            case "constructor":
                                $this->constructor = $method;
                                break;
                            case "function":
                                $this->functions[$method->name] = $method;
                                break;
                            case "fallback":
                                $this->fallback = $method;
                                break;
                        }
                        break;
                    case "event":
                        // Todo: parse events
                        break;
                    default:
                        throw new ContractABIException(
                            sprintf('Bad/Unexpected value for ABI block param "type" at index %d', $index)
                        );
                }
            } catch (ContractABIException $e) {
                // Trigger an error instead of throwing exception if a block within ABI fails,
                // to make sure rest of ABI blocks will work
                trigger_error(sprintf('[%s] %s', get_class($e), $e->getMessage()));
            }

            $index++;
        }
    }

    /**
     * @param string $name
     * @param array|null $args
     * @return string
     * @throws ContractABIException
     * @throws \Exception
     */
    public function encodeCall(string $name, ?array $args): string
    {
        $method = $this->functions[$name] ?? null;
        if (!$method instanceof Method) {
            throw new ContractABIException(sprintf('Call method "%s" is undefined in ABI', $name));
        }

        $givenArgs = $args;
        $givenArgsCount = is_array($givenArgs) ? count($givenArgs) : 0;
        $methodParams = $method->inputs;
        $methodParamsCount = is_array($methodParams) ? count($methodParams) : 0;

        // Strict mode
        if ($this->strictMode) {
            // Params/args count must match
            if ($methodParamsCount || $givenArgsCount) {
                if ($methodParamsCount !== $givenArgsCount) {
                    throw new ContractABIException(
                        sprintf('Method "%s" requires %d args, given %d', $name, $methodParamsCount, $givenArgsCount)
                    );
                }
            }
        }

        $encoded = "";
        $methodParamsTypes = [];
        for ($i = 0; $i < $methodParamsCount; $i++) {
            /** @var MethodParam $param */
            $param = $methodParams[$i];
            $arg = $givenArgs[$i];
            $encoded .= $this->encodeArg($param->type, $arg);
            $methodParamsTypes[] = $param->type;
        }

        $encodedMethodCall = Keccak::hash(sprintf('%s(%s)', $method->name, implode(",", $methodParamsTypes)), 256);
        return '0x' . substr($encodedMethodCall, 0, 8) . $encoded;
    }

    /**
     * @param string $type
     * @param $value
     * @return string
     * @throws ContractABIException
     */
    public function encodeArg(string $type, $value): string
    {
        $len = preg_replace('/[^0-9]/', '', $type);
        if (!$len) {
            $len = null;
        }

        $type = preg_replace('/[^a-z]/', '', $type);
        switch ($type) {
            case "hash":
            case "address":
                if (substr($value, 0, 2) === "0x") {
                    $value = substr($value, 2);
                }
                break;
            case "uint":
            case "int":
                $value = Integers::Pack_UInt_BE($value);
                break;
            case "bool":
                $value = $value === true ? 1 : 0;
                break;
            case "string":
                $value = ASCII::base16Encode($value)->hexits(false);
                break;
            default:
                throw new ContractABIException(sprintf('Cannot encode value of type "%s"', $type));
        }

        return substr(str_pad(strval($value), 64, "0", STR_PAD_LEFT), 0, 64);
    }

    /**
     * @param string $name
     * @param string $encoded
     * @return array
     * @throws ContractABIException
     */
    public function decodeResponse(string $name, string $encoded): array
    {
        $method = $this->functions[$name] ?? null;
        if (!$method instanceof Method) {
            throw new ContractABIException(sprintf('Call method "%s" is undefined in ABI', $name));
        }

        // Remove suffix "0x"
        if (substr($encoded, 0, 2) === '0x') {
            $encoded = substr($encoded, 2);
        }

        // Output params
        $methodResponseParams = $method->outputs ?? [];
        $methodResponseParamsCount = count($methodResponseParams);

        // What to expect
        if ($methodResponseParamsCount <= 0) {
            return [];
        } elseif ($methodResponseParamsCount === 1) {
            // Put all in a single chunk
            $chunks = [$encoded];
        } else {
            // Split in chunks of 64 bytes
            $chunks = str_split($encoded, 64);
        }


        $result = []; // Prepare
        for ($i = 0; $i < $methodResponseParamsCount; $i++) {
            /** @var MethodParam $param */
            $param = $methodResponseParams[$i];
            $chunk = $chunks[$i];
            $decoded = $this->decodeArg($param->type, $chunk);

            if ($param->name) {
                $result[$param->name] = $decoded;
            } else {
                $result[] = $decoded;
            }
        }

        return $result;
    }

    /**
     * @param string $type
     * @param string $encoded
     * @return bool|\Comely\DataTypes\BcNumber|string
     * @throws ContractABIException
     */
    public function decodeArg(string $type, string $encoded)
    {
        $len = preg_replace('/[^0-9]/', '', $type);
        if (!$len) {
            $len = null;
        }
        $type = preg_replace('/[^a-z]/', '', $type);

        $encoded = ltrim($encoded, "0");
        switch ($type) {
            case "hash":
            case "address":
                return '0x' . $encoded;
            case "uint":
            case "int":
                return Integers::Unpack($encoded)->value();
            case "bool":
                return boolval($encoded);
            case "string":
                return ASCII::base16Decode(new Base16($encoded));
            default:
                throw new ContractABIException(sprintf('Cannot encode value of type "%s"', $type));
        }
    }
}
