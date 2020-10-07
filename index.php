<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Transaction\TxBuilder;
use VchainThor\Vchain;

require_once "vendor/autoload.php";

$localUrl = "185.244.248.29";
$port = 8669;

$Vchain = new Vchain($localUrl,$port);
try {
    $v = '';
    echo "<pre>";
//    $parm = "0x9bcc6526a76ae560244f698805cc001977246cb92c2b4f1e2b7a204e445409ea";
//    $params  = ["raw"=>"0xf86981ba800adad994000000000000000000000000000000000000746f82271080018252088001c0b8414792c9439594098323900e6470742cd877ec9f9906bca05510e421f3b013ed221324e77ca10d3466b32b1800c72e12719b213f1d4c370305399dd27af962626400"];
//    $v =  $Vchain->tranaction()->TransactionPost($params);
//    $v = $Vchain->tranaction()->Transactions($parm);
//    $v = $Vchain->tranaction()->TransactionsReceipt($parm);
//    $v = $Vchain->Accounts()->AccountsAddress('0x5034aa590125b64023a0262112b98d72e3c8e40e');
//    $v = $Vchain->Accounts()->AccountsCode('0x5034aa590125b64023a0262112b98d72e3c8e40e');
//    $v = $Vchain->Accounts()->AccountsStorage("0x5034aa590125b64023a0262112b98d72e3c8e40e","0x0000000000000000000000000000000000000000000000000000000000000001");
//    $v = $Vchain->Blocks()->Blocks('best');
//    $v = $Vchain->Blocks()->Blocks('best');
//    var_dump($v);

    $tx = new TxBuilder();
    $tx->setChainTag(39);
    $tx->setBlockRef([0, 3, 1, 0, 0xaa, 0xbb, 0xcc, 0xdd]); //fix
    $tx->setExpiration(18); //fix
    $tx->setClauses(array('to' => '9fdee3753061cc9033f8bcfb9fd81c18cc137f05', "value" => 20000, 'data' => array(0, 0, 0)));
    $tx->setGasPriceCoef(39);
    $tx->setGas(21);
    $tx->setDependsOn("");
    $tx->setNonce(101); //random genrated by code
    $privat_key = 'bdc36247f43a67086dd73046ed63851607b943bbc9373bd00f017cde1b22365c';
    $base16_private = new Base16();
    $b_pri = $base16_private->set($privat_key);
    $tx->setPrivate($b_pri);
    $tx = $tx->build_tx();
    var_dump($tx);
}catch (Exception $e){
    echo $e->getMessage();
}
