<?php
declare(strict_types=1);

namespace VchainThor\Transaction;

use Comely\DataTypes\Buffer\Base16;
use deemru\Blake2b;
use FurqanSiddiqui\ECDSA\Curves\Secp256k1;
use VchainThor\Exception\IncompleteTxException;
use VchainThor\RLP;

class TxBuilder
{
    /** @var int */
    private int $chainTag;
    /** @var int */
    private int $blockRef;
    /** @var int */
    private int $expiration;

    /**@var array*/
    private array $clauses;

    /** @var int */
    private int $gasPriceCoef;

    /** @var int */
    private int $gas;

    /** @var string|null */
    private ?string $dependsOn;

    /** @var int */
    private int $nonce;

    /** @var Base16 */
    private Base16 $private_key;

    /**
     * @param Base16 $private
     */
    public function setPrivate(Base16 $private){
        $this->private_key = $private;
    }

    /**
     * @param int $chainTag
     */
    public function setChainTag(int $chainTag): void
    {
        $this->chainTag = $chainTag;
    }

//    /**
//     * @param array $blockRef
//     * @throws IncompleteTxException
//     */
//    public function setBlockRef(array $blockRef): void
//    {
//        $data='';
//        foreach ($blockRef as $dt) {
//            $data .=$this->encodeSingleByteInt($dt);
//        }
//        $this->blockRef = Integers::Unpack($data);
//    }
    /**
     * @param int blockRef
     * @throws IncompleteTxException
     */
    public function setBlockRef(int $blockRef): void
    {
        $this->blockRef =$blockRef;
    }

    /**
     * @param int $expiration
     */
    public function setExpiration(int $expiration): void
    {
        $this->expiration = $expiration;
    }

    /**
     * @param array $clauses
     * @throws IncompleteTxException
     */
    public function setClauses(array $clauses): void
    {
        $data = '';
        if($clauses['data']){
            foreach ($clauses['data'] as $dt) {
                $data .=$this->encodeSingleByteInt($dt);
            }
        }
        $clauses['data'] =$data;
//        $clauses['data'] =$clauses['data'];
        $this->clauses = $clauses;
    }

    /**
     * @param int $gasPriceCoef
     */
    public function setGasPriceCoef(int $gasPriceCoef): void
    {
        $this->gasPriceCoef = $gasPriceCoef;
    }

    /**
     * @param int $gas
     */
    public function setGas(int $gas): void
    {
        $this->gas = $gas;
    }

    /**
     * @param string|null $dependsOn
     */
    public function setDependsOn(?string $dependsOn): void
    {
        $this->dependsOn = $dependsOn;
    }

    /**
     * @param int $nonce
     */
    public function setNonce(int $nonce): void
    {
        $this->nonce = $nonce;
    }

    /**
     * @param int $int
     * @return string
     * @throws IncompleteTxException
     */
    private function encodeSingleByteInt(int $int): string
    {
        if ($int > 0xff) {
            throw new IncompleteTxException('Greater Then 256');
        }

        $hex = dechex($int);
        if (strlen($hex) % 2 !== 0) {
            $hex = "0" . $hex;
        }
        return $hex;
    }

    /**
     * @return string
     * @throws IncompleteTxException
     */
    public function build_tx(){
        $rlp = new RLP();

        $txBodyObj = new RLP\RLPObject();
        if (!isset($this->chainTag)) {
            throw new IncompleteTxException('ChainTag value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->chainTag);

        if (!isset($this->blockRef)) {
            throw new IncompleteTxException('BlockRef value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->blockRef);
        if (!isset($this->chainTag)) {
            throw new IncompleteTxException('Expiration value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->expiration);
        if (!isset($this->clauses)) {
            throw new IncompleteTxException('Clause value is not set or is invalid');
        }
        $clause1 = new RLP\RLPObject();
        $clause1->encodeHexString($this->clauses['to']);
        $clause1->encodeInteger(pow($this->clauses['value'],18));
        if($this->clauses['data']) {
            $clause1->encodeHexString($this->clauses['data']);
        }else{
            $clause1->encodeHexString('');
        }
        $clausesObj = new RLP\RLPObject();
        $clausesObj->encodeObject($clause1);
        $txBodyObj->encodeObject($clausesObj);
        if (!isset($this->gasPriceCoef)) {
            throw new IncompleteTxException('Gas Price Coef value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->gasPriceCoef);
        if (!isset($this->gas)) {
            throw new IncompleteTxException('Gas value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->gas);
        if (!isset($this->dependsOn)) {
            throw new IncompleteTxException('DependsOn value is not set or is invalid');
        }
        $txBodyObj->encodeString($this->dependsOn);
        if (!isset($this->nonce)) {
            throw new IncompleteTxException('Nonce value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->nonce);
        $txBodyObj->encodeObject(new RLP\RLPObject());
        $tx_encode = $txBodyObj->getRLPEncoded($rlp)->toString();
        $blk2b = new Blake2b();
        $hash = $blk2b->hash($tx_encode);
        $hash_tx = bin2hex($hash);
        $base16_msg = new Base16();
        $b_msg = $base16_msg->set($hash_tx);
        $secp = new Secp256k1();
        if (!isset($this->private_key)) {
            throw new IncompleteTxException('Private Key is not Set');
        }
        $sign = $secp->sign($this->private_key,$b_msg);
        $txBodyObj->encodeHexString($sign->r()->value().$sign->s()->value().'00');
        $tx_encode = $txBodyObj->getRLPEncoded($rlp)->toString();
        return $tx_encode;
    }
}