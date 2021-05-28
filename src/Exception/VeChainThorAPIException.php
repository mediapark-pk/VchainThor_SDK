<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Exception;


/**
 * Class VeChainThorAPIException
 * @package VchainThor\Exception
 */
class VeChainThorAPIException extends VeChainThorException
{
    public static function unexpectedResultType(string $method, string $expected, string $got): self
    {
        return new self(
            sprintf('Method [%s] expects result type %s, got %s', $method, strtoupper($expected), strtoupper($got))
        );
    }
}