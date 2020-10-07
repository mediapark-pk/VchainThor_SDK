<?php
namespace VchainThor\Transaction;

use Vchainthor\Exception\VchainTransactionException;
use VchainThor\HttpClient;

class Transaction{
    private HttpClient $http;
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function Transactions(string $id){
        if ($id=='') {
            throw new VchainTransactionException("id must not empty");
        }
        return $this->http->sendRequest('transactions/'.$id);
    }

    public function TransactionsReceipt(string $id){
        if ($id=='') {
            throw new VchainTransactionException("id must not empty");
        }
        return $this->http->sendRequest('transactions/'.$id."/receipt");
    }

    public function TransactionPost(array $param){
        if (!$param) {
            throw new VchainTransactionException("array must not empty");
        }
        return $this->http->sendRequest('transactions',$param,[],"POST");
    }
}