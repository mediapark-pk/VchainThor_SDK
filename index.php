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
    $account = $Vchain->Accounts()->SHaAccountAddress('9fdee3753061cc9033f8bcfb9fd81c18cc137f05');
    $account = $Vchain->Accounts()->GetVTA_VTHO_SHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290');

    $v = $Vchain->Blocks()->Blocks('best');
    $tx = new TxBuilder();
    $tx->setChainTag(39);
    $tx->setBlockRef($v['number']);
    $tx->setExpiration(); //fix
    $tx->setClausesVET('0x3D7f2E12945987aD44CB7d06CE420aF23948a290', 1); //VTE
    $tx->setClausesVET('0x3D7f2E12945987aD44CB7d06CE420aF23948a290', 1); //VTE
    $tx->setClausesSHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290',1); //SHA
    $tx->setClausesSHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290',1); //SHA
    $tx->setGasPriceCoef(0);
    $tx->setGas(958000 );
    $tx->setDependsOn("");
    $tx->setNonce(); //random genrated by code
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
