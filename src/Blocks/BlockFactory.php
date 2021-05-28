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
     * @param string $id
     * @param string $expanded
     * @return Block
     * @throws VeChainThorAPIException
     * @throws VechainThorBlocksException
     */
    public function getBlockById(string $id, string $expanded): Block
    {
        return $this->getBlock($id, $expanded);
    }

    /**
     * @param int $num
     * @param string $expanded
     * @return Block
     * @throws VeChainThorAPIException
     * @throws VechainThorBlocksException
     */
    public function getBlockByNumber(int $num, string $expanded): Block
    {
        return $this->getBlock($num, $expanded);
    }

    /**
     * @param $blockIdentifier
     * @param string $expanded
     * @return Block
     * @throws VeChainThorAPIException
     * @throws VechainThorBlocksException
     */
    public function getBlock($blockIdentifier, string $expanded = "true"): Block
    {
        if ($blockIdentifier == '') {
            throw new VechainThorBlocksException("First Args must not empty");
        }

        $result = $this->http->sendRequest("blocks/$blockIdentifier?expanded=$expanded");

        if (!is_array($result) || !$result) {
            throw VeChainThorAPIException::unexpectedResultType("blocks/blockIdentifier?expanded=bool", "object", gettype($result));
        }

        return new Block($result);
    }
}