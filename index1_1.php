<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Transaction\TxBuilderTwofunc;
use VchainThor\Vchain;

require_once "vendor/autoload.php";

$localUrl = "185.244.248.29";
$port = 8669;

function randomNumber($length): int
{
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }
    return (int)$result;
}

$Vchain = new Vchain($localUrl, $port);
try {
    $v = $Vchain->Blocks()->Blocks('best');
//    echo $v['number'];
//    echo '<br>';
//    $v = 7212818;
    echo $chose = $v['number'] + 18;
    echo '<br>';
    $ch = \VchainThor\Math\Integers::Pack_UInt_BE($chose);
//    $ch =   "6E0832";
    $ch=    str_pad($ch, 8, "0", STR_PAD_LEFT);
//    exit();
//    var_dump(\VchainThor\Math\Integers::Unpack($ch)->value());

    $ch=    str_split($ch, 2);
    $ch[]   =   0;
    $ch[]   =  0;
    $ch[]   =  0;
    $ch[]   =  0;
//    var_dump($ch);
//    echo '<br>';
//    var_dump($ch[0],hexdec($ch[0]));
//    echo '<br>';
//    var_dump($ch[1],hexdec($ch[1]));
//    echo '<br>';
//    var_dump($ch[2],hexdec($ch[2]));
//    echo '<br>';
//    var_dump($ch[3],hexdec($ch[3]));
//    echo '<br>';

    $ch2 = [];
    foreach($ch as $chi) {
        if(is_string($chi)) {
            $ch2[]  =   hexdec($chi);
            continue;
        }
        $ch2[]  =   $chi;
    }
    var_dump($ch2);

//    $ar = $ch2;
//
//exit;
//    $dt = str_split($ch, 1);
//    //print_r($dt);
//    $ar = array();
//    $j = 1;
//    $ar[] = 0;
//    echo "<pre>";
//    for ($i = 0; $i < count($dt); $i++) {
//        $ar[$j] = '0x' . $dt[$i] . $dt[++$i];
//        $j++;
//    }
//    $ar[] = 0;
//    $ar[] = 0;
//    $ar[] = 0;
//    $ar[] = 0;
//    exit('yes');
//    var_dump($ar);
//    exit();

    $ar = [0, 0x24, 0x0f, 0x6e, 0, 0, 0, 0];



    function test($int)
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

    $code = '';
    foreach ($ch2 as $a) {
        $code .= test($a);
    }
//    echo '<br>';
//    echo $code;
//    echo '<br>';
//    echo $int = \VchainThor\Math\Integers::Unpack($code)->value();
//    echo '<br>';
//    $code = '';
//    foreach ($ar as $a) {
//        $code .= test($a);
//    }
//
//    echo $code;
//    echo '<br>';
//    echo $int = \VchainThor\Math\Integers::Unpack($code)->value();
//    exit;
    $int = \VchainThor\Math\Integers::Unpack($code)->value();
    $tx = new TxBuilderTwofunc();
    $tx->setChainTag(39);
    $tx->setBlockRef((int)$int);
    $tx->setExpiration(18); //fix
    $tx->setClauses(array('to' => '9fdee3753061cc9033f8bcfb9fd81c18cc137f05', "value" => 1, 'data' => array(0)));
    $tx->setGasPriceCoef(0);
    $tx->setGas(35000);
    $tx->setDependsOn("");
    $tx->setNonce(randomNumber(3)); //random genrated by code
    $privat_key = 'bdc36247f43a67086dd73046ed63851607b943bbc9373bd00f017cde1b22365c';
    $base16_private = new Base16();
    $b_pri = $base16_private->set($privat_key);
    $tx->setPrivate($b_pri);
    $v = $tx->build_tx();
    var_dump($v);
} catch (Exception $e) {
    echo $e->getMessage();
}