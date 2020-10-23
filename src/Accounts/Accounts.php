<?php
namespace VchainThor\Accounts;

use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use Vchainthor\Exception\VchainAccountsException;
use Vchainthor\Exception\VchainAPIException;
use VchainThor\HttpClient;

class Accounts
{
    private HttpClient $http;
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
    public function GetVetVthoSha(string $address):array
    {
        $vta = $this->GetVET($address);
        unset($vta['hasCode']);
        $vta['SHA'] = $this->GetSHA($address);
        return $vta;
    }

    /**
     * @param string $address
     * @return float
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainAPIException
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

    public function AccountsCode(string $address){
        if ($address=="") {
            throw new VchainAccountsException("Address must not empty");
        }
        return $this->http->sendRequest('accounts/'.$address."/code");
    }

    public function AccountsStorage(string $address,string $key){
        if ($address=="") {
            throw new VchainAccountsException("Address must not empty");
        }
        if ($key=="") {
            throw new VchainAccountsException("Key must not empty");
        }
        return $this->http->sendRequest('accounts/'.$address."/storage/".$key);
    }
}
