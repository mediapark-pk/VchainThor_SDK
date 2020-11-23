<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Transaction\TxBuilder;

use VchainThor\Vchain;

require_once "vendor/autoload.php";

$localUrl = "185.244.248.29";
$port = 8669;

$Vchain = new Vchain($localUrl, $port);
try {
//    $pri = $Vchain->keyPairs()->privateKeyFromEntropy(hash("sha256","youshouldcare"));
//    echo "<h1>Private Key</h1><br>";
//    var_dump($pri->base16()->value());
//    echo "<br>";
//    echo "<h1>Public Key</h1><br>";
//    var_dump($pri->publicKey()->getAccountAddress());
//    exit();

//    exit();
//    $eth = new \FurqanSiddiqui\Ethereum\Ethereum();
//    $prv1 = $eth->keyPairs()->privateKeyFromEntropy(hash("sha256","whocares"));
//    var_dump($prv1);
//    exit();

//    $account = $Vchain->Accounts()->GetBalance('0x3D7f2E12945987aD44CB7d06CE420aF23948a290');

//    $account = $Vchain->Accounts()->GetVET('0x3D7f2E12945987aD44CB7d06CE420aF23948a290');
//    var_dump($account);exit();
//    $account = $Vchain->Accounts()->GetVTA_VTHO_SHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290');

//    $private_key = new \FurqanSiddiqui\Ethereum\KeyPair\KeyPairFactory();
//    $pri = $private_key->generateSecurePrivateKey();
//    var_dump($pri);
//
//    exit();
    $bestBlock = $Vchain->Blocks()->Blocks('best');
    echo $bestBlock['number']+18;
    $tx = new TxBuilder();
    $tx->setChainTag(39);
    $tx->setBlockRef($bestBlock['number']);
    $tx->setExpiration(); //fix
//    $tx->setClausesVET('0x3D7f2E12945987aD44CB7d06CE420aF23948a290', 1);
//    $tx->setClausesVET('0x3D7f2E12945987aD44CB7d06CE420aF23948a290', 1);
    $tx->setClausesVTHO('0x3D7f2E12945987aD44CB7d06CE420aF23948a290',1);
    $tx->setClausesSHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290',1);
//    $tx->setClausesSHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290',1);
//    $tx->setClausesSHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290',1);
    $tx->setGasPriceCoef(0);
    $tx->setGas(21005 );
    $tx->setDependsOn("");
    $tx->setNonce();
    $privat_key = 'afc16047f43a67086dd73046ed63851607b943bbc9373bd00f017cde1b42365b';
    $base16_private = new Base16();
    $b_pri = $base16_private->set($privat_key);
    $tx->setPrivate($b_pri);
    echo '<pre>';
    $v = $tx->build_tx();
    var_dump($v);
    echo '<pre>';
    $re = $Vchain->Tranaction()->TransactionPost($v);
    var_dump($re);
} catch (Exception $e) {
    echo $e->getMessage();
}
