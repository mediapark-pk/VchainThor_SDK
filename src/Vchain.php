<?php

namespace VchainThor;

use FurqanSiddiqui\BIP32\ECDSA\Curves;
use VchainThor\Accounts\Accounts;
use VchainThor\Blocks\Blocks;
use VchainThor\KeyPair\KeyPairFactory;
use VchainThor\Transaction\Transaction;

class Vchain
{
    /** @var Transaction */
    private Transaction $transaction;
    /** @var Accounts */
    Private Accounts $account;
    /** @var Blocks */
    private Blocks $blocks;

    /** @var int ECDSA/ECC curve identifier */
    public const ECDSA_CURVE = Curves::SECP256K1;
    /** @var int Fixed length of private keys in bits */
    public const PRIVATE_KEY_BITS = 256;

    /** @var KeyPairFactory */
    private KeyPairFactory $keyPairFactory;

    /**
     * Vchain constructor.
     * @param string $ip
     * @param int|null $port
     * @param string|null $username
     * @param string|null $password
     */
    public function __construct(string $ip, ?int $port = NULL, ?string $username = "", ?string $password = "")
    {
        $httpClient = new HttpClient($ip, $port, $username, $password);
        $this->transaction = new Transaction($httpClient);
        $this->account = new Accounts($httpClient);
        $this->blocks = new Blocks($httpClient);
        $this->keyPairFactory = new KeyPairFactory($this);
    }

    /**
     * @return KeyPairFactory
     */
    public function keyPairs(): KeyPairFactory
    {
        return $this->keyPairFactory;
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
