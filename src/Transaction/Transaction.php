<?php
declare(strict_types=1);

namespace MediaParkpk\VeChainThor\Transaction;

use MediaParkpk\VeChainThor\Exception\VechainThorTransactionException;
use MediaParkpk\VeChainThor\HttpClient;

class Transaction{
    private HttpClient $http;
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function Transactions(string $id){
        if ($id=='') {
            throw new VechainThorTransactionException("id must not empty");
        }
        return $this->http->sendRequest('transactions/'.$id);
    }

    public function TransactionsReceipt(string $id){
        if ($id=='') {
            throw new VechainThorTransactionException("id must not empty");
        }
        return $this->http->sendRequest('transactions/'.$id."/receipt");
    }

    public function TransactionPost(string $encodedtx){
        if (!$encodedtx) {
            throw new VechainThorTransactionException("array must not empty");
        }
        $param = array("raw"=> '0x'.$encodedtx);
        return $this->http->sendRequest('transactions',$param,[],"POST");
    }
}
