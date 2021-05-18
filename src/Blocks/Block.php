<?php
namespace MediaParkPK\VeChainThor\Blocks;

use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use MediaParkPK\VeChainThor\Exception\VechainThorAPIException;
use MediaParkPK\VeChainThor\Exception\VechainThorBlocksException;
use MediaParkPK\VeChainThor\HttpClient;

/**
 * Class Block
 * @package VchainThor\Blocks\
 */
class Block{
    /** @var HttpClient  */
    private HttpClient $http;

    /**
     * Block constructor.
     * @param HttpClient $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $block
     * @param bool $expanded
     * @return array
     * @throws VechainThorAPIException
     * @throws VechainThorBlocksException
     */
    public function Blocks(string $block,bool $expanded=true): array
    {
        if ($block=='') {
            throw new VechainThorBlocksException("First Args must not empty");
        }
        return $this->http->sendRequest('blocks/'.$block."?expanded=".(($expanded==1)?'true':'false'));
    }
}