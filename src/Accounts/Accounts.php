<?php
namespace VchainThor\Accounts;

use Vchainthor\Exception\VchainAccountsException;
use VchainThor\HttpClient;

class Accounts
{
    private HttpClient $http;
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function VTA_VTHO_SHA(string $address):array
    {
        $vta = $this->AccountsAddress($address);
        $sha = $this->SHaAccountaddress($address);
        return array('VET balance'=>$vta['balance'],'VTHO (energy)'=>$vta['energy'],'SHA balance'=>$sha);
    }

    public function SHaAccountaddress(string $address):float
    {
        if(substr($address,0,2)=='0x'){
            $address = substr($address,2);
        }
        $addres = "0x"."70a08231".str_pad($address,64,'0',STR_PAD_LEFT);
        $param = array('data'=>$addres);
        $response = $this->http->sendRequest('accounts/0xa1bcfa20a82eca70a5af5420b11bc53a279024ec',$param,[],"POST");
        return $res =(float) (hexdec($response['data'])/pow(10,18));
    }

    public function AccountsAddress(string $address){
        if ($address=="") {
            throw new VchainAccountsException("Address must not empty");
        }
        $account =  $this->http->sendRequest('accounts/'.$address);
        $account['balance'] = hexdec($account['balance'])/pow(10,18);
        $account['energy'] = hexdec($account['energy'])/pow(10,18);
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