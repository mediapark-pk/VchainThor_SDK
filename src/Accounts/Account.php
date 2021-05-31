<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Accounts;

use MediaParkPK\VeChainThor\Exception\VeChainThorAccountsException;
use MediaParkPK\VeChainThor\Exception\VeChainThorAPIException;
use MediaParkPK\VeChainThor\Exception\VeChainThorException;
use MediaParkPK\VeChainThor\HttpClient;
use MediaParkPK\VeChainThor\Validate;

/**
 * Class Account
 * @package MediaParkpk\VeChainThor\Account
 */
class Account
{
    /**
     * @var HttpClient
     */
    private HttpClient $http;

    /**
     * Account constructor.
     * @param HttpClient $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $address
     * @return array
     * @throws VeChainThorAPIException
     * @throws VeChainThorAccountsException
     * @throws VeChainThorException
     */
    public function getBalance(string $contractAddress, string $address): array
    {
        $vta = $this->getVET($address);
        $vta['SHA'] = $this->getSHA($contractAddress, $address);
        return $vta;
    }

    /**
     * @param string $contractAddress
     * @param string $address
     * @return float
     * @throws VeChainThorAPIException
     * @throws VeChainThorException
     */
    public function getSHA(string $contractAddress, string $address): float
    {
        if (!Validate::Address($address)) {
            throw new VeChainThorException("invalid address provided to getSHA");
        }

        if(substr($address,0,2) == '0x'){
            $address = substr($address,2);
        }

        $address = "0x" ."70a08231". str_pad($address,64,'0',STR_PAD_LEFT);

        $params = [
            'data' => $address
        ];

        $result = $this->http->sendRequest("accounts/$contractAddress", $params, [],"POST");
        return (float) (hexdec($result['data'])/pow(10,18));
    }

    /**
     * @param string $address
     * @return array
     * @throws VeChainThorAPIException
     * @throws VeChainThorAccountsException|VeChainThorException
     */
    public function getVET(string $address): array
    {
        if (!Validate::Address($address)) {
            throw new VeChainThorException("invalid address provided to getVET");
        }

        $result =  $this->http->sendRequest("accounts/$address");

        if (!is_array($result) || !$result) {
            throw VeChainThorAPIException::unexpectedResultType("accounts/{address}", "object", gettype($result));
        }

        $result['balance'] = hexdec($result['balance'])/pow(10,18);
        $result['energy'] = hexdec($result['energy'])/pow(10,18);
        unset($result['hasCode']);

        return $result;
    }

    /**
     * @param string $address
     * @return array
     * @throws VeChainThorAPIException
     * @throws VeChainThorAccountsException
     * @throws VeChainThorException
     */
    public function accountsCode(string $address): array
    {
        if (!Validate::Address($address)) {
            throw new VeChainThorException("invalid address provided to accountsCode");
        }

        $result = $this->http->sendRequest("accounts/$address/code");

        if (!is_array($result) || !$result) {
            throw VeChainThorAPIException::unexpectedResultType("accounts/{address}/code", "object", gettype($result));
        }

        return $result;
    }

    /**
     * @param string $address
     * @param string $key
     * @return array
     * @throws VeChainThorAPIException
     * @throws VeChainThorAccountsException|VeChainThorException
     */
    public function accountsStorage(string $address, string $key): array
    {
        if(!Validate::Address($address)) {
            throw new VeChainThorException("Invalid address provided to accountsStorage");
        }

        $result = $this->http->sendRequest("accounts/$address/storage/$key");

        if (!is_array($result) || !$result) {
            throw VeChainThorAPIException::unexpectedResultType("accounts/{address}/storage/{key}", "object", gettype($result));
        }

        return $result;
    }
}
