<?php
namespace MediaParkPK\VeChainThor\Blocks;

use MediaParkPK\VeChainThor\Exception\VeChainThorAPIException;
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
     * @param string $id
     * @param string $expanded
     * @return Block
     * @throws VeChainThorAPIException
     */
    public function getBlockById(string $id, string $expanded = "true"): Block
    {
        return $this->getBlock($id, $expanded);
    }

    /**
     * @param int $num
     * @param string $expanded
     * @return Block
     * @throws VeChainThorAPIException
     */
    public function getBlockByNumber(int $num, string $expanded = "true"): Block
    {
        return $this->getBlock($num, $expanded);
    }

    /**
     * @param $blockIdentifier
     * @param string $expanded
     * @return Block
     * @throws VeChainThorAPIException
     */
    public function getBlock($blockIdentifier, string $expanded = "true"): Block
    {
        $result = $this->http->sendRequest("blocks/$blockIdentifier?expanded=$expanded");

        if (!is_array($result) || !$result) {
            throw VeChainThorAPIException::unexpectedResultType("blocks/blockIdentifier?expanded=bool", "object", gettype($result));
        }

        return new Block($result);
    }
}