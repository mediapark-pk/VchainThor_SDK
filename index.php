<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Math\Integers;
use VchainThor\Transaction\MultiTxBuilder;
use VchainThor\Vchain;

require_once "vendor/autoload.php";

$localUrl = "185.244.248.29";
$port = 8669;

function randomNumber(int $length): int
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
    echo $chose = $v['number'] + 18;
    echo '&nbsp;&nbsp;&nbsp;New Block<br>';
    $ch = Integers::Pack_UInt_BE($chose);
    $ch =  str_pad($ch, 8, "0", STR_PAD_LEFT);
    $ch =  str_split($ch, 2);
    $ch[] =  0;
    $ch[] =  0;
    $ch[] =  0;
    $ch[] =  0;
    $ch2  = [];
    foreach($ch as $chi)
    {
        if(is_string($chi))
        {
            $ch2[]  =   hexdec($chi);
            continue;
        }
        $ch2[]  =   $chi;
    }

    $tx = new MultiTxBuilder();
    $code = '';
    foreach ($ch2 as $a)
    {
        $code .= $tx::DectoHex($a);
    }

    $int = (int) Integers::Unpack($code)->value();
    $tx->setChainTag(39);
    $tx->setBlockRef($int);
    $tx->setExpiration(18); //fix

    $tx->setClauses(array('to' => '9fdee3753061cc9033f8bcfb9fd81c18cc137f05', "value" => 0, 'data' => array(0)));
    $tx->setClauses(array('to' => '0xa1bcfa20a82eca70a5af5420b11bc53a279024ec', "value" => 0, 'data' => array("transfer(address,uint256)",'0x0a82c9083a3f16c9837295b5caf21656e84cfda5','1')),true);
//    var_dump($tx->clauses);
//    exit();
    $tx->setGasPriceCoef(0);
    $tx->setGas(958000);
    $tx->setDependsOn("");
    $tx->setNonce(randomNumber(3)); //random genrated by code
//    $tx->setNonce(847); //random genrated by code
    $privat_key = 'afc16047f43a67086dd73046ed63851607b943bbc9373bd00f017cde1b42365b';
    $base16_private = new Base16();
    $b_pri = $base16_private->set($privat_key);
    $tx->setPrivate($b_pri);
    echo '<pre>';
    $v = $tx->build_tx();
    var_dump($v);
} catch (Exception $e) {
    echo $e->getMessage();
}