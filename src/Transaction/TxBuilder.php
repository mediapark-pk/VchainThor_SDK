<?php
declare(strict_types=1);

namespace VchainThor\Transaction;

use Comely\DataTypes\Buffer\Base16;
use deemru\Blake2b;
use FurqanSiddiqui\ECDSA\Curves\Secp256k1;
use FurqanSiddiqui\ECDSA\ECC\Math;
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

    private int $sha;
    private int $vta;

    public function __construct()
    {
        $this->sha = 0;
        $this->vta = 0;
    }

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
    public function setBlockRef(int $chose): void
    {
        echo $chose = $chose + 18;
        $ch = Integers::Pack_UInt_BE($chose);
        $ch = str_pad($ch, 8, "0", STR_PAD_LEFT);
        $ch = str_split($ch, 2);
        $ch[] = 0;
        $ch[] = 0;
        $ch[] = 0;
        $ch[] = 0;
        $ch2 = [];
        foreach ($ch as $chi) {
            if (is_string($chi)) {
                $ch2[] = hexdec($chi);
                continue;
            }
            $ch2[] = $chi;
        }
        $tx = new TxBuilder();
        $code = '';
        foreach ($ch2 as $a) {
            $code .= $tx::DectoHex($a);
        }
        $int = (int)Integers::Unpack($code)->value();
        $this->blockRef = $int;
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
    public function setClauses(array $clauses, bool $ssh = false): void
    {
        $data = '';
        if ($ssh) {
            $this->sha++;
            $this->loop = 2;
            $keccek_hash = Keccak::hash($clauses['data'][0], 256);
            $first_8_keccek_hash = substr($keccek_hash, 0, 8);
            $to = '';
            if (substr($clauses['data'][1], 0, 2) == '0x') {
                $to = substr($clauses['data'][1], 2);
            } else {
                $to = $clauses['data'][1];
            }
            $to_with_pad = str_pad($to, 64, "0", STR_PAD_LEFT);
            $value_power_hex = dechex($clauses['data'][2] * pow(10, 18));
            $value_send = str_pad($value_power_hex, 64, "0", STR_PAD_LEFT);
            $data = $first_8_keccek_hash . $to_with_pad . $value_send;
        } else {
            $this->vta++;
            if ($clauses['data']) {
                foreach ($clauses['data'] as $dt) {
                    $data .= $this->encodeSingleByteInt($dt);
                }
            }
        }
        $clauses['data'] = $data;
        $this->clauses[] = $clauses;
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
    public function setNonce(): void
    {
        $this->nonce = $this->randomNumber(3);
//        $this->nonce = 148;
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
        if (!isset($this->chainTag)) {
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

//        $pointR = $sign->curvePointR();
////        if($this->vta==1 && $this->sha==0) {
//        $bits = gmp_strval($pointR->y(), 2);
//        var_dump($bits);
//        echo '<br>';

//        $bits1 = str_replace("0", "", $bits);
//        var_dump($bits1);
////        var_dump(strlen($bits1));
//        echo '<br>';
////        $bits0 = str_replace("1", "", $bits);
////        var_dump($bits0);
////        var_dump(strlen($bits0));
//        echo '<br>';
//        $parity ='';
//        if(strlen($bits1)%2==0){
//            $parity = '00';
//        }else{
//            $parity = '01';
//        }
//        echo '<br>';
//        print_r($pointR->y());
//        echo '<br>';
//        $parity1 = strlen(str_replace("0", "", gmp_strval($pointR->y(), 2))) % 2 === 0 ? "00" : "01";
//        $parity2 = strlen(str_replace("0", "", gmp_strval(gmp_init($sign->r()->value() . $sign->s()->value(), 16), 2))) % 2 === 0 ? "00" : "01";
//        $parity = strlen(str_replace("1", "", gmp_strval($pointR->y(), 2))) % 2 === 0 ? "00" : "01";
//        }
//        else if($this->vta>1 && $this->sha==0){
//            $parity = strlen(str_replace("0", "", gmp_strval($pointR->y(), 2))) % 2 === 0 ? "01" : "00";
//        }
//        else if($this->sha>0){
//            $parity = '00';
//        }

        var_dump($flag);
        $v = $flag - 31 === 0 ? "00" : "01";
        $txBodyObj->encodeHexString($sign->r()->value() . $sign->s()->value() . $v);
        $tx_encode = $txBodyObj->getRLPEncoded($rlp)->toString();
        return $tx_encode;
    }
}
