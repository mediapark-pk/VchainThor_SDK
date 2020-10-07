<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Transaction\TxBuilder;
use VchainThor\Vchain;

require_once "vendor/autoload.php";

$localUrl = "185.244.248.29";
$port = 8669;

function randomNumber($length):int {
    $result = '';

    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return (int) $result;
}

$Vchain = new Vchain($localUrl,$port);
try {
//    echo "<pre>";
//    var_dump(dechex('7204194'));exit();
//    $v = '';
//    $parm = "0x9bcc6526a76ae560244f698805cc001977246cb92c2b4f1e2b7a204e445409ea";
//    $params  = ["raw"=>"0xf86981ba800adad994000000000000000000000000000000000000746f82271080018252088001c0b8414792c9439594098323900e6470742cd877ec9f9906bca05510e421f3b013ed221324e77ca10d3466b32b1800c72e12719b213f1d4c370305399dd27af962626400"];
//    $v =  $Vchain->tranaction()->TransactionPost($params);
//    $v = $Vchain->tranaction()->Transactions($parm);
//    $v = $Vchain->tranaction()->TransactionsReceipt($parm);
//    $v = $Vchain->Accounts()->AccountsAddress('0x5034aa590125b64023a0262112b98d72e3c8e40e');
//    $v = $Vchain->Accounts()->AccountsCode('0x5034aa590125b64023a0262112b98d72e3c8e40e');
//    $v = $Vchain->Accounts()->AccountsStorage("0x5034aa590125b64023a0262112b98d72e3c8e40e","0x0000000000000000000000000000000000000000000000000000000000000001");
//    $v = $Vchain->Blocks()->Blocks('best');

    $v = $Vchain->Blocks()->Blocks('best');
//    echo $v["id"]."<br>";
//    $sub_str = substr($v["id"],2,16);
//    echo "<br>";
//    echo $chose_hash =substr($sub_str,8);
//    exit();
//    $chose = hexdec($chose);
//    echo $chose = $v['number'];
//    echo "<br>";
//    $chose = $v['number'];
//    echo '<br>';
    echo $chose = $v['number']+18;
    echo '<br>';
//    echo dechex($chose);
//    exit();
//    echo '<br>';
//    dechex($chose);
//    exit();
    $ch = dechex($chose);
//    echo $ch;
    $dt= str_split($ch, 1);
    print_r($dt);

    echo "<br>";

    $ar = array();
    $j=1;
    $ar[] = 0;
    for($i=0;$i<count($dt);$i++){
        $ar[$j] = '0x'.$dt[$i].$dt[++$i];
        $j++;
    }
    $ar[] = 0;
    $ar[] = 0;
    $ar[] = 0;
    $ar[] = 0;
    print_r($ar);
//    exit();

//    $dt= str_split($chose_hash, 1);
//    $j=0;
//    for($i=0;$i<count($dt);$i++){
//        $ar[] = $dt[$i].$dt[++$i];
//    }
//    print_r($ar);
//    exit();
    $ar = [0, 0x6D, 0xEF, 0xA1, 0, 0, 0, 0];
    function test($int){
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
    foreach ($ar as $a){
        //echo test($a);
        $code .=test($a);

    }
//    echo $code;
//    exit();
//    echo $code;exit();
//    echo $code.'<br>Code<br>';
    $int = \VchainThor\Math\Integers::Unpack($code)->value();
//    print_r($int);
//    exit();
//    $v = $Vchain->Blocks()->Blocks('best');
//    echo $v["id"]."<br>";
//    echo $sub_str = substr($v["id"],2,16);
//    echo $chose =(int) substr($sub_str,8);
//    $chose = hexdec($chose);
//    $chose = $v['number']+18;
//
//    echo $chose = dechex($chose);
//
//    echo "<br>";
//    echo $chose = \VchainThor\Math\Integers::Unpack($chose);
//    exit();
    $tx = new TxBuilder();
    $tx->setChainTag(39);
//    $tx->setBlockRef([0, 3, 1, 0, 0xaa, 0xbb, 0xcc, 0xdd]);
//    echo $v['number'] ."<br>";
//    echo $v['number']+18;
//    exit();
    $tx->setBlockRef((int) $int);
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
}catch (Exception $e){
    echo $e->getMessage();
}

//f86c27836de9fd12dddc949fdee3753061cc9033f8bcfb9fd81c18cc137f05824e208300000027158065c0b84154eb4f884b93f7b8231aa3ad89c74e274891619ada3e6a731f91e0baf322f9422e4e6214841bab69bb53c2c76b12647f0487ecdfd64a153b65baf39df4fc7a7e01
