<?php
declare(strict_types=1);

namespace VchainThor\Exception;

/**
 * Class VchainException
 * @package Vchain\Exception
 */
class VchainException extends \Exception
{
    /**
     * @param string $method
     * @param string $expected
     * @param string $got
     * @return static
     */
    public static function unexpectedResultType(string $method, string $expected, string $got): self
    {
        return new self(
            sprintf('Method [%s] expects result type %s, got %s', $method, strtoupper($expected), strtoupper($got))
        );
    }
}
