<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Accounts;

use MediaParkPK\VeChainThor\Exception\VechainThorAccountsException;
use MediaParkPK\VeChainThor\Exception\VechainThorAPIException;
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
     * @throws VechainThorAPIException
     * @throws VechainThorAccountsException
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
     * @throws VechainThorAPIException
     */
    public function GetSHA(string $address):float
    {
        if(substr($address,0,2)=='0x'){
            $address = substr($address,2);
        }
        $address = "0x"."70a08231".str_pad($address,64,'0',STR_PAD_LEFT);
        $param = array('data'=>$address);
        $response = $this->http->sendRequest('accounts/0xa1bcfa20a82eca70a5af5420b11bc53a279024ec',$param,[],"POST");
        return (float) (hexdec($response['data'])/pow(10,18));
    }

    /**
     * @param string $address
     * @return array
     * @throws VechainThorAPIException
     * @throws VechainThorAccountsException
     */
    public function GetVET(string $address): array
    {
        if ($address=="") {
            throw new VechainThorAccountsException("Address must not empty");
        }
        $account =  $this->http->sendRequest('accounts/'.$address);
        $account['balance'] = hexdec($account['balance'])/pow(10,18);
        $account['energy'] = hexdec($account['energy'])/pow(10,18);
        unset($account['hasCode']);
        return $account;
    }

    /**
     * @param string $address
     * @return array
     * @throws VechainThorAPIException
     * @throws VechainThorAccountsException
     */
    public function AccountsCode(string $address): array
    {
        if ($address=="") {
            throw new VechainThorAccountsException("Address must not empty");
        }
        return $this->http->sendRequest('accounts/'.$address."/code");
    }

    /**
     * @param string $address
     * @param string $key
     * @return array
     * @throws VechainThorAPIException
     * @throws VechainThorAccountsException
     */
    public function AccountsStorage(string $address, string $key): array
    {
        if ($address=="") {
            throw new VechainThorAccountsException("Address must not empty");
        }
        if ($key=="") {
            throw new VechainThorAccountsException("Key must not empty");
        }
        return $this->http->sendRequest('accounts/'.$address."/storage/".$key);
    }
}
