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
    $bestBlock = $Vchain->Blocks()->Blocks('best');
    $tx = new TxBuilder();
    $tx->setChainTag(39);
    $tx->setBlockRef($bestBlock['number']);
    $tx->setExpiration(); //fix
//    $tx->setClausesVET('0x3D7f2E12945987aD44CB7d06CE420aF23948a290', 1);
    $tx->setGasPriceCoef(0);
    $tx->setGas(21005 );
    $tx->setDependsOn("");
    $tx->setNonce();
    $privat_key = '';
    $base16_private = new Base16();
    $b_pri = $base16_private->set($privat_key);
    $tx->setPrivate($b_pri);
    echo '<pre>';
    $v = $tx->build_tx();
    var_dump($v);

} catch (Exception $e) {
    echo $e->getMessage();
}
