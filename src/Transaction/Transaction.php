<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Transaction;

use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use MediaParkPK\VeChainThor\Exception\VechainThorAPIException;
use MediaParkPK\VeChainThor\Exception\VechainThorTransactionException;
use MediaParkPK\VeChainThor\HttpClient;

/**
 * Class Transaction
 * @package MediaParkPK\VeChainThor\Transaction
 */
class Transaction{
    /** @var HttpClient  */
    private HttpClient $http;

    /**
     * Transaction constructor.
     * @param HttpClient $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $id
     * @return array
     * @throws VechainThorTransactionException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VechainThorAPIException
     */
    public function Transactions(string $id): array
    {
        if ($id=='') {
            throw new VechainThorTransactionException("id must not empty");
        }
        return $this->http->sendRequest('transactions/'.$id);
    }

    /**
     * @param string $id
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VechainThorAPIException
     * @throws VechainThorTransactionException
     */
    public function TransactionsReceipt(string $id){
        if ($id=='') {
            throw new VechainThorTransactionException("id must not empty");
        }
        return $this->http->sendRequest('transactions/'.$id."/receipt");
    }

    /**
     * @param string $encodedTx
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VechainThorAPIException
     * @throws VechainThorTransactionException
     */
    public function TransactionPost(string $encodedTx): array
    {
        if (!$encodedTx) {
            throw new VechainThorTransactionException("array must not empty");
        }
        $param = array("raw"=> '0x'.$encodedTx);
        return $this->http->sendRequest('transactions',$param,[],"POST");
    }
}
