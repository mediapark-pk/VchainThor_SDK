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

    public function AccountsAddress(string $address){
        if ($address=="") {
            throw new VchainAccountsException("Address must not empty");
        }
        return $this->http->sendRequest('accounts/'.$address);
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