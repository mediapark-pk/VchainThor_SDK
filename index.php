<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Math\Integers;
use VchainThor\Transaction\MultiTxBuilder;
use VchainThor\Transaction\TxBuilder;
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
    $account = $Vchain->Accounts()->AccountsAddress('0x3D7f2E12945987aD44CB7d06CE420aF23948a290');
    echo hexdec($account['balance']);echo '<br>';
    echo hexdec($account['balance'])/pow(10,18);
    echo '<br>';
    echo hexdec($account['energy']);echo '<br>';
    echo hexdec($account['energy'])/pow(10,18);
    echo '<br>';
    var_dump($account);
    exit();
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

//    $tx = new MultiTxBuilder();
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

    $tx->setClauses(array('to' => '0x3D7f2E12945987aD44CB7d06CE420aF23948a290', "value" => 1, 'data' => array(0))); //9fdee3753061cc9033f8bcfb9fd81c18cc137f05
//    $tx->setClauses(array('to' => '0xa1bcfa20a82eca70a5af5420b11bc53a279024ec', "value" => 0, 'data' => array("transfer(address,uint256)",'0x3D7f2E12945987aD44CB7d06CE420aF23948a290','1')),true); //0x0a82c9083a3f16c9837295b5caf21656e84cfda5
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

//0xd4c801bd8b542186967abdfd73db733de5734f324b78d720d3742528934dac98   multi add
//0x4c1dbc46bb0ba615c7e43d781480f357d7919cd8adc9b266242f9a8c69a27a35   multi add

//0xb76a75e57593d61e97c6fd4930b35f2f13864b2d0b154fd7782a0b32dafaf6a8    SHA not add
//0x6572358a61d30587cfada94e4626897436d53fad638357ecf8b6681723be27df    SHA not add
//0x7484045d50dd9be10637676dc6d4ea42e4166742ca30171279c8f5955be62fdb    SHA not add
//0xebe38d9f04a1d8ed1bfc07128635a55553cf83db94491b79e3d3267231b506ca    SHA not add
//0xd5b928f34cfafee3cce5fd0736d55ed0ed007ac98a283000214a3d61dead1b52    SHA not add
//0xd23c169a852f33ca8493b0c2c2ddab21969d54ffa50d7a00f43867304f9dd20a    SHA not add
//0x091338e9a762b40e26943b67fa25ce486e7b07676a2759271f94d237ea17afad    SHA not add

//0x091338e9a762b40e26943b67fa25ce486e7b07676a2759271f94d237ea17afad    SHA add
//0x9726c488598b73a21db50dfdd4753bd93e7e0e29daf15879771b3e68e1eeee1b    VET add
//0x9f9be638582b17636b405bcc59b86677bd5fc64700b35b3fa693e0d254187a8b    Not add
//0xb4c12b6fc59794559b4dea29841b605e3b7263605e656f0660c1c136298ff549    NOt add

//0x2df267ea48af4ad158b16da4ace13b89ea85dcda0bb5209b53e2c5e91ff868a7    VET add
//0x9f334e7f1efeab0d5b7e518c1998ca2aefe7a7f8a3bee61c74caa0e312b3220f    VET add

//0x3618f9d33720fb7658dceb0fb8c6f6d5c553fef2a356fd309d83f7721e9cc8c4    VET not add