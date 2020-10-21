<?php
declare(strict_types=1);

namespace VchainThor\Transaction;

use Comely\DataTypes\Buffer\Base16;
use deemru\Blake2b;
use Exception;
use FurqanSiddiqui\ECDSA\Curves\Secp256k1;
use VchainThor\Exception\IncompleteTxException;
use VchainThor\Keccak;
use VchainThor\Math\Integers;
use VchainThor\RLP;

class TxBuilder
{
    /** @var int */
    private int $chainTag;
    /** @var int */
    private int $blockRef;
    /** @var int */
    private int $expiration;
    /**@var array */
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
    /** @var int */
    private int $SHA;
    /** @var int */
    private int $SHAGasPrice;
    /** @var int */
    private int $VET;
    /** @var int */
    private int $VETGasPrice;

    /**
     * TxBuilder constructor.
     */
    public function __construct()
    {
        $this->VET = 0;
        $this->SHA = 0;
        $this->VETGasPrice = 21005;
        $this->SHAGasPrice = 102740;
    }

    /**
     * @param $int
     * @return string
     * @throws IncompleteTxException
     */
    public static function DectoHex($int)
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
     * @param int $length
     * @return int
     */
    function randomNumber(int $length): int
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return (int)$result;
    }

    /**
     * @param Base16 $private
     */
    public function setPrivate(Base16 $private)
    {
        $this->private_key = $private;
    }

    /**
     * @param int $chainTag
     */
    public function setChainTag(int $chainTag): void
    {
        $this->chainTag = $chainTag;
    }

    /**
     * @param int blockRef
     * @throws IncompleteTxException
     */
    public function setBlockRef(int $CurrentblockNumber): void
    {
        $NextBlockNumber = $CurrentblockNumber + 18;
        $ch = Integers::Pack_UInt_BE($NextBlockNumber);
        $ch = str_pad($ch, 8, "0", STR_PAD_LEFT);
        $ch = str_split($ch, 2);
        $ch[] = 0;
        $ch[] = 0;
        $ch[] = 0;
        $ch[] = 0;
        $dec_array = [];
        foreach ($ch as $chi) {
            if (is_string($chi)) {
                $dec_array[] = hexdec($chi);
                continue;
            }
            $dec_array[] = $chi;
        }
        $tx = new TxBuilder();
        $code = '';
        foreach ($dec_array as $a) {
            $code .= $tx::DectoHex($a);
        }
        $int = (int)Integers::Unpack($code)->value();
        $this->blockRef = $int;
    }

    public function setExpiration(int $expiration=18): void
    {
        $this->expiration = $expiration;
    }

    /**
     * @param string $to
     * @param int $amount
     * @throws IncompleteTxException
     */
    public function setClausesVET(string $to,int $amount): void
    {
        $this->VET++;
        $clauses = array('to' => $to, "value" => $amount, 'data' => array(0));
        $data = '';
        if ($clauses['data']) {
            foreach ($clauses['data'] as $dt) {
                $data .= $this->encodeSingleByteInt($dt);
            }
        }
        $clauses['data'] = $data;
        $this->clauses[] = $clauses;
    }

    /**
     * @param string $to
     * @param int $amount
     * @throws Exception
     */
    public function setClausesSHA(string $to,int $amount):void
    {
        $this->SHA++;
        $clauses_data = array('to' => '0xa1bcfa20a82eca70a5af5420b11bc53a279024ec', "value" => 0, 'data' => array("transfer(address,uint256)",'0x3D7f2E12945987aD44CB7d06CE420aF23948a290','1'));
        $keccek_hash = Keccak::hash($clauses_data['data'][0], 256);
        $first_8_keccek_hash = substr($keccek_hash, 0, 8);
        if (substr($to, 0, 2) == '0x') {
            $to = substr($to, 2);
        }
        $to_with_pad = str_pad($to, 64, "0", STR_PAD_LEFT);
        $value_power_hex = dechex($amount * pow(10, 18));
        $value_send = str_pad($value_power_hex, 64, "0", STR_PAD_LEFT);
        $data = $first_8_keccek_hash . $to_with_pad . $value_send;
        $clauses_data['data'] = $data;
        $this->clauses[] = $clauses_data;
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

    public function setNonce(): void
    {
        $this->nonce = $this->randomNumber(12);
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
    public function build_tx()
    {
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
        if (!isset($this->expiration)) {
            throw new IncompleteTxException('Expiration value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->expiration);
        if (!isset($this->clauses)) {
            throw new IncompleteTxException('Clause value is not set or is invalid');
        }

        $clausesObj = new RLP\RLPObject();
        foreach ($this->clauses as $clse) {
            $val = $clse['value'] * pow(10, 18);
            $clause1 = new RLP\RLPObject();
            $clause1->encodeHexString($clse['to']);
            $clause1->encodeInteger($val);
            if (!isset($clse['data'])) {
                throw new IncompleteTxException('Data Array must be set');
            }
            if ($clse['data']) {
                $clause1->encodeHexString($clse['data']);
            } else {
                $clause1->encodeHexString('');
            }
            $clausesObj->encodeObject($clause1);
        }
        $txBodyObj->encodeObject($clausesObj);
        if (!isset($this->gasPriceCoef))
        {
            throw new IncompleteTxException('Gas Price Coef value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->gasPriceCoef);
        
        if($this->VET>0 && $this->SHA==0){
            $this->gas = $this->VETGasPrice * $this->VET;
        }else if($this->VET==0 && $this->SHA>0){
            $this->gas = $this->SHAGasPrice * $this->SHA;
        }else if($this->VET>0 && $this->SHA>0){
            $this->gas = ($this->SHAGasPrice * $this->SHA)+($this->VETGasPrice * $this->VET);
        }
        if (!isset($this->gas))
        {
            throw new IncompleteTxException('Gas value is not set');
        }
        $txBodyObj->encodeInteger($this->gas);
        if (!isset($this->dependsOn))
        {
            throw new IncompleteTxException('DependsOn value is not set or is invalid');
        }
        $txBodyObj->encodeString($this->dependsOn);
        if (!isset($this->nonce))
        {
            throw new IncompleteTxException('Nonce value is not set or is invalid');
        }
        $txBodyObj->encodeInteger($this->nonce);
        $txBodyObj->encodeObject(new RLP\RLPObject());
        $tx_encode = $txBodyObj->getRLPEncoded($rlp)->toString();
        $blk2b = new Blake2b();
        $hash = $blk2b->hash(hex2bin($tx_encode));//afde
        $hash_tx = bin2hex($hash);
        $base16_msg = new Base16();
        $b_msg = $base16_msg->set($hash_tx);
        $secp = new Secp256k1();
        if (!isset($this->private_key)) {
            throw new IncompleteTxException('Private Key is not Set');
        }
        $pub_key    =   $secp->getPublicKey($this->private_key);
        $sign = $secp->sign($this->private_key, $b_msg);
        $flag   =   $secp->findRecoveryId($pub_key, $sign, $b_msg, true);
        $v = $flag - 31 === 0 ? "00" : "01";
        $txBodyObj->encodeHexString($sign->r()->value() . $sign->s()->value() . $v);
        $tx_encode = $txBodyObj->getRLPEncoded($rlp)->toString();
        return $tx_encode;

    }
}
