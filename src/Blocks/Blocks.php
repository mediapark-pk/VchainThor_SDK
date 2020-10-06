<?php
namespace Vchain\Blocks;


use Vchain\Exception\VchainAPIException;
use Vchain\Exception\VchainBlocksException;
use Vchain\HttpClient;

class Blocks{
    private HttpClient $http;
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function Blocks(string $block,bool $expanded=true){
        if ($block=='') {
            throw new VchainBlocksException("First Args must not empty");
        }
        return $this->http->sendRequest('blocks/'.$block."?expanded=".(($expanded==1)?'true':'false'));
    }
}