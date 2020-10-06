<?php

namespace Vchain;

use Vchain\Accounts\Accounts;
use Vchain\Blocks\Blocks;
use Vchain\Transaction\Transaction;

class Vchain
{
    /** @var string */
    private string $ip;
    /** @var int */
    private ?int $port = NULL;
    /** @var string */
    private string $username;
    /** @var string */
    private string $password;
    /**
     * @var Transaction
     */
    private Transaction $transaction;
    Private Accounts $account;
    private Blocks $blocks;


    /**
     * Vchain constructor.
     * @param Generic $generic
     */
    public function __construct(string $ip, ?int $port = NULL, ?string $username = "", ?string $password = "")
    {
        $httpClient = new HttpClient($ip, $port, $username, $password);
        $this->transaction = new Transaction($httpClient);
        $this->account = new Accounts($httpClient);
        $this->blocks = new Blocks($httpClient);
    }

    /**
     * @return Transaction
     */
    public function Tranaction():Transaction
    {
        return $this->transaction;
    }

    /**
     * @return Accounts
     */
    public function Accounts():Accounts
    {
        return $this->account;
    }

    /**
     * @return Blocks
     */
    public function Blocks():Blocks
    {
        return $this->blocks;
    }

}