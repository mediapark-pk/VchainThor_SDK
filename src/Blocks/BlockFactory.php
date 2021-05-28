<?php
namespace MediaParkPK\VeChainThor\Blocks;

use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use MediaParkPK\VeChainThor\Exception\VeChainThorAPIException;
use MediaParkPK\VeChainThor\Exception\VechainThorBlocksException;
use MediaParkPK\VeChainThor\HttpClient;

/**
 * Class BlockFactory
 * @package VchainThor\Blocks\
 */
class BlockFactory{
    /** @var HttpClient  */
    private HttpClient $http;

    /**
     * BlockFactory constructor.
     * @param HttpClient $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string|int $block
     * @param bool $expanded
     * @return Block
     * @throws VeChainThorAPIException
     * @throws VechainThorBlocksException
     */
    private function getBlock($block,bool $expanded=true): Block
    {
        if ($block == '') {
            throw new VechainThorBlocksException("First Args must not empty");
        }

        $expanded = $expanded ? "true" : "false";

        $result = $this->http->sendRequest("blocks/$block?expanded=$expanded");

        if (!is_array($result) || !$result) {
            throw VeChainThorAPIException::unexpectedResultType("blocks", "object", gettype($result));
        }

        return new Block($result);
    }
}