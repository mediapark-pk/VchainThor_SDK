<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Transaction;

use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use http\Exception\InvalidArgumentException;
use MediaParkPK\VeChainThor\Exception\VeChainThorAPIException;
use MediaParkPK\VeChainThor\Exception\VeChainThorTransactionException;
use MediaParkPK\VeChainThor\HttpClient;

/**
 * Class TxFactory
 * @package MediaParkPK\VeChainThor\TxFactory
 */
class TxFactory{
    /** @var HttpClient  */
    private HttpClient $http;

    /**
     * TxFactory constructor.
     * @param HttpClient $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $txId
     * @return Transaction
     * @throws VeChainThorAPIException
     */
    public function getTransactionById(string $txId): Transaction
    {
        if (!$txId) {
            throw new \InvalidArgumentException("TxFactory id is required");
        }

        $result = $this->http->sendRequest("transactions/$txId");

        if (!is_array($result) || !$result) {
            throw VeChainThorAPIException::unexpectedResultType("transactions/txId", "array", gettype($result));
        }

        return New Transaction($result);
        }

    /**
     * @param string $id
     * @return array
     * @throws VeChainThorAPIException
     */
    public function transactionsReceipt(string $id) : array
    {
        if (!$id) {
            throw new \InvalidArgumentException("TxFactory id is required");
        }
        return $this->http->sendRequest("transactions/$id/receipt");
    }

    /**
     * @param string $encodedTx
     * @return array
     * @throws VeChainThorAPIException
     * @throws VeChainThorTransactionException
     */
    public function transactionPost(string $encodedTx): array
    {
        if (!$encodedTx) {
            throw new VeChainThorTransactionException("array must not empty");
        }
        $param = array("raw"=> '0x'.$encodedTx);
        return $this->http->sendRequest('transactions',$param,[],"POST");
    }
}
