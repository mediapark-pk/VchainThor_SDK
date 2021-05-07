<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Accounts;

use MediaParkPK\VeChainThor\HttpClient;

/**
 * Class Account
 * @package MediaParkpk\VeChainThor\Account
 */
class Account
{
    /**
     * @var HttpClient
     */
    private HttpClient $http;

    /**
     * Account constructor.
     * @param HttpClient $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $address
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainAPIException
     * @throws VchainAccountsException
     */
    public function GetBalance(string $address):array
    {
        $vta = $this->GetVET($address);
        unset($vta['hasCode']);
        $vta['SHA'] = $this->GetSHA($address);
        return $vta;
    }

    /**
     * @param string $address
     * @return float
     * @throws \Comely\Http\Exception\HttpRequestException
     * @throws \Comely\Http\Exception\HttpResponseException
     * @throws \Comely\Http\Exception\SSL_Exception
     * @throws \MediaParkpk\VeChainThor\Exception\VechainThorAPIException
     */
    public function GetSHA(string $address):float
    {
        if(substr($address,0,2)=='0x'){
            $address = substr($address,2);
        }
        $addres = "0x"."70a08231".str_pad($address,64,'0',STR_PAD_LEFT);
        $param = array('data'=>$addres);
        $response = $this->http->sendRequest('accounts/0xa1bcfa20a82eca70a5af5420b11bc53a279024ec',$param,[],"POST");
        return $res =(float) (hexdec($response['data'])/pow(10,18));
    }

    /**
     * @param string $address
     * @return mixed
     */
    public function GetVET(string $address){
        if ($address=="") {
            throw new VchainAccountsException("Address must not empty");
        }
        $account =  $this->http->sendRequest('accounts/'.$address);
        $account['balance'] = hexdec($account['balance'])/pow(10,18);
        $account['energy'] = hexdec($account['energy'])/pow(10,18);
        unset($account['hasCode']);
        return $account;
    }

    /**
     * @param string $address
     * @return mixed
     */
    public function AccountsCode(string $address){
        if ($address=="") {
            throw new VchainAccountsException("Address must not empty");
        }
        return $this->http->sendRequest('accounts/'.$address."/code");
    }

    /**
     * @param string $address
     * @param string $key
     * @return mixed
     */
    public function AccountsStorage(string $address, string $key){
        if ($address=="") {
            throw new VchainAccountsException("Address must not empty");
        }
        if ($key=="") {
            throw new VchainAccountsException("Key must not empty");
        }
        return $this->http->sendRequest('accounts/'.$address."/storage/".$key);
    }
}
