<?php

namespace MediaParkPK\VeChainThor;

use FurqanSiddiqui\BIP32\ECDSA\Curves;
use MediaParkPK\VeChainThor\Accounts\Account;
use MediaParkPK\VeChainThor\Blocks\BlockFactory;
use MediaParkPK\VeChainThor\KeyPair\KeyPairFactory;
use MediaParkPK\VeChainThor\Transaction\TxFactory;

class VeChain
{
    /** @var TxFactory */
    private TxFactory $transaction;
    /** @var Account  */
    Private Account $account;
    /** @var BlockFactory */
    private BlockFactory $blocks;

    /** @var int ECDSA/ECC curve identifier */
    public const ECDSA_CURVE = Curves::SECP256K1;
    /** @var int Fixed length of private keys in bits */
    public const PRIVATE_KEY_BITS = 256;

    /** @var KeyPairFactory */
    private KeyPairFactory $keyPairFactory;

    /**
     * VeChain constructor.
     * @param string $ip
     * @param int|null $port
     * @param string|null $username
     * @param string|null $password
     */
    public function __construct(string $ip, ?int $port = NULL, ?string $username = "", ?string $password = "")
    {
        $httpClient = new HttpClient($ip, $port, $username, $password);
        $this->transaction = new TxFactory($httpClient);
        $this->account = new Account($httpClient);
        $this->blocks = new BlockFactory($httpClient);
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
     * @return TxFactory
     */
    public function transaction() : TxFactory
    {
        return $this->transaction;
    }

    /**
     * @return Account
     */
    public function accounts(): Account
    {
        return $this->account;
    }

    /**
     * @return BlockFactory
     */
    public function blocks() : BlockFactory
    {
        return $this->blocks;
    }

}
