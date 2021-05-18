<?php

namespace MediaParkPK\VeChainThor;

use FurqanSiddiqui\BIP32\ECDSA\Curves;
use MediaParkPK\VeChainThor\Accounts\Account;
use MediaParkPK\VeChainThor\Blocks\Block;
use MediaParkPK\VeChainThor\KeyPair\KeyPairFactory;
use MediaParkPK\VeChainThor\Transaction\Transaction;

class Vchain
{
    /** @var Transaction */
    private Transaction $transaction;
    /** @var Account  */
    Private Account $account;
    /** @var Block */
    private Block $blocks;

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
        $this->account = new Account($httpClient);
        $this->blocks = new Block($httpClient);
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
    public function Transaction():Transaction
    {
        return $this->transaction;
    }

    /**
     * @return Account
     */
    public function Accounts(): Account
    {
        return $this->account;
    }

    /**
     * @return Block
     */
    public function Blocks() : Block
    {
        return $this->blocks;
    }

}
