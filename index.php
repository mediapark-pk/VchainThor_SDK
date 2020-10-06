<?php
declare(strict_types=1);

require_once "vendor/autoload.php";

$localUrl = "185.244.248.29";
$port = 8669;

$Vchain = new \Vchain\Vchain($localUrl,$port);
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
    var_dump($v);
}catch (Exception $e){
    echo $e->getMessage();
}
