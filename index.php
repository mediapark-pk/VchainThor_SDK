<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Math\Integers;
use VchainThor\Transaction\TxBuilder;

use VchainThor\Vchain;

require_once "vendor/autoload.php";

$localUrl = "185.244.248.29";
$port = 8669;

$Vchain = new Vchain($localUrl, $port);
try {
//    $account = $Vchain->Accounts()->SHaAccountaddress('9fdee3753061cc9033f8bcfb9fd81c18cc137f05');
//    $account = $Vchain->Accounts()->VTA_VTHO_SHA('0x3D7f2E12945987aD44CB7d06CE420aF23948a290');
//    $account = $Vchain->Accounts()->VTA_VTHO_SHA('0x0a82c9083a3f16c9837295b5caf21656e84cfda5');
//    $account = $Vchain->Accounts()->SHaAccountaddress('3D7f2E12945987aD44CB7d06CE420aF23948a290');
//    $account = $Vchain->Accounts()->AccountsAddress('0x3D7f2E12945987aD44CB7d06CE420aF23948a290');
//    var_dump($account);
//    exit();
//    echo dechex(01);
//    exit();
//    echo hexdec($account['balance']);echo '<br>';
//    echo hexdec($account['balance'])/pow(10,18);
//    echo '<br>';
//    echo hexdec($account['energy']);echo '<br>';
//    echo hexdec($account['energy'])/pow(10,18);
//    echo '<br>';
//    var_dump($account);
//    exit();
    $v = $Vchain->Blocks()->Blocks('best');

    $tx = new TxBuilder();
    $tx->setChainTag(39);
    $tx->setBlockRef($v['number']);
//    $tx->setBlockRef(7307837);
    $tx->setExpiration(18); //fix
    $tx->setClauses(array('to' => '0x3D7f2E12945987aD44CB7d06CE420aF23948a290', "value" => 1, 'data' => array(0))); //VTE
    $tx->setClauses(array('to' => '0x3D7f2E12945987aD44CB7d06CE420aF23948a290', "value" => 1, 'data' => array(0))); //VTE
    //9fdee3753061cc9033f8bcfb9fd81c18cc137f05
//    $tx->setClauses(array('to' => '0xa1bcfa20a82eca70a5af5420b11bc53a279024ec', "value" => 0, 'data' => array("transfer(address,uint256)",'0x3D7f2E12945987aD44CB7d06CE420aF23948a290','1')),true); //0x0a82c9083a3f16c9837295b5caf21656e84cfda5 //smart contract
//    $tx->setClauses(array('to' => '0x3D7f2E12945987aD44CB7d06CE420aF23948a290', "value" => 1, 'data' => array(0))); //VTE
//    $tx->setClauses(array('to' => '0xa1bcfa20a82eca70a5af5420b11bc53a279024ec', "value" => 0, 'data' => array("transfer(address,uint256)",'0x3D7f2E12945987aD44CB7d06CE420aF23948a290','1')),true); //0x0a82c9083a3f16c9837295b5caf21656e84cfda5 //smart contract
//    $tx->setClauses(array('to' => '0xa1bcfa20a82eca70a5af5420b11bc53a279024ec', "value" => 0, 'data' => array("transfer(address,uint256)",'0x3D7f2E12945987aD44CB7d06CE420aF23948a290','1')),true); //0x0a82c9083a3f16c9837295b5caf21656e84cfda5 //smart contract
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
    echo '<br>';
//    $decode = \VchainThor\RLP::Decode($v);
//    print_r($decode);
//    var_dump($decode[0][9]);
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
//0x35d90a55c6d10718a64a95aa995592f51deec9fe2c993a7c9c632b746a67adea    SHA add

//0x939362320db49d3d68629a5e7e4c7fb70bff2f45bf5eb1e914039ec4e165b05a    VET not add
//0x5e2f38ab0f5ebc28abfd5ff91e5a58d5380ab462bd52d9a6f9caf3614cc3a4c9    VET add
//0xceca60acb86234a7eddc4d61049efea555ae550b19e8ca489ea2cbd9c9491504    VET add
//0xfbf822d69c055cf741f3a4ac3b3181b4bb22a9c4da79e6a091329c4e18e810f0    VET

//0xc6e6e617d11d57791c9978fe4084a0853a435c95826a15865c356ecbb923f3f4    VET
//0x90166e5f931588a57b3f46bfe716a2ae61e5322a20b214af5c2eca44e5cfe62a    VET add
//0x4fa939f61018d7b98cd98ee97015efd4c6f6c8380547927292e20af0d3863808    VET add
//0xa33149fb12faaf882c7fc5bbab84285b9e33c2a7f091a412506092b1b52e3f60


//0xe338d412dd181ea8c1ff80421dbc4c73d0406fb7301c95091fe3a223f7572207    VET Multi Add
//0x413d32dcb919a4d487221a0f8186107145a3f5be69d2ee0ef8fad7ce974c861a    VET Multi Add
//0x242211dcdd96800908d360a637622d87808c019de80886b1d18743ddd6845611    VET Multi Add
//0x78b855bc9c0fe56c1f1dfa8261f929b9f87b4bebd15020aaf0e4c3eca56b7255    VET Multi not add
//0xd5f3899055ad92ab175465055cbff668748e48ca93643f4849f32353c4bb2627    VET Multi not add
//0xbf3812927ccdf4059a7012467923279df21b376f2273dd4322ec9160e13d23fd    VET Multi Add
//0xbc1b30df3c01a673effcf3df4353895cccb1d8bc84c05687c89621876e5bb94b    VET multi Add
//0x99a5ed32fbf353537675d4d15ef0865e5bda60d5cb2b1235cecdd7206357f661    VET multi Add
//0x3bfe0939e574fc957187cf27cd3f26dc799a9028743598d660ea4d67c8f35374    VET MULTI and SHA not add
//0xaaec4aaa9aa53606ef4bc5c5a3acbf96fd6b427a8c8be566c4b7caadc03984c1    VET MULTI and SHA add          //0xf9011727876f81b80000000012f8bedf943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000f85c94a1bcfa20a82eca70a5af5420b11bc53a279024ec80b844a9059cbb0000000000000000000000003D7f2E12945987aD44CB7d06CE420aF23948a2900000000000000000000000000000000000000000000000000de0b6b3a764000080830e9e3080820147c0b841d2703455ab8149feee1fa2c77d01a144455ac96438abd4f73d72645d73afc04079b102408b892583d1e0db5e3163e70671f1ca373d15aa57aa6e6a400b5eb2ad01
//0x4dcb51e9736b9c453f323ebb8900900a44aa8ccfe65e89c3f2d8ae9243472502    VET MULTI and SHA not add     //0xf9011727876f81d20000000012f8bedf943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000f85c94a1bcfa20a82eca70a5af5420b11bc53a279024ec80b844a9059cbb0000000000000000000000003D7f2E12945987aD44CB7d06CE420aF23948a2900000000000000000000000000000000000000000000000000de0b6b3a764000080830e9e3080820145c0b84134362a839367818cdf9a90ebd40fb9f72b1d26fc2ae0c4dca36939f96ebc198068d7508fa5d80355f895b5b67553a8e182881b83c05da6d75cecbc85ad5e879700

//0x479e0ffed700b7ba279df084c949659e950297a7192b8604f8c6f2213e806cd0    VET(1 clause) add  //0xf87827876f82020000000012e0df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a76400000080830e9e30808203c5c0b84136f9892c30591f19dec0b73e484fb18a2b90c5958c759feafa7571e56e76c23f336f50516fbb86aa39e5e923c50eb146fbd22d22765755b8e0ba58dde784014501
//f87727876f820e0000000012e0df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a76400000080830e9e30808201e3c0b84025fae04da149bb03e8d25506856051988a864ab6d7516ebb2f5890e1e72b96ec62e9a08d4d83b6fa126303f28eb366123010b4e0806581a3a9b1aa3c614f7400 //invalid signature length
//0x46d59e8901ef37ef139d0754b8a04becd9d1649a2f919c0e7a75b27f7d395a0d   VEt(1 clause) add  //0xf87727876f82150000000012e0df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a76400000080830e9e308081a1c0b841ab8727aff8b55e88f09349fe123380e2ce34b1f9663ec57860466fe3b78001203520910ac19a6eb94505bda01319b84815fa9b030544cea83cdc64f4e6d2270f01
//0xf7eab88f4d279e8c2bd7895c7510102d77e060c33edef5704e29cb693c44901e   VET and SHA       //f8d627876f82450000000012f87edf943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000f85c94a1bcfa20a82eca70a5af5420b11bc53a279024ec80b844a9059cbb0000000000000000000000003D7f2E12945987aD44CB7d06CE420aF23948a2900000000000000000000000000000000000000000000000000de0b6b3a764000080830e9e30808194c0b841595f02cb8b8da5bdc096d901351fbf8287585af4cf9435d634eca447194fb6b950b678140e4cfded91abd600fb99c5f5a6187781bc5521c52a64a3e3f9518a2700
//0xf7eab88f4d279e8c2bd7895c7510102d77e060c33edef5704e29cb693c44901e   VET not add   //0xf86981ba800adad994000000000000000000000000000000000000746f82271080018252088001c0b8414792c9439594098323900e6470742cd877ec9f9906bca05510e421f3b013ed221324e77ca10d3466b32b1800c72e12719b213f1d4c370305399dd27af962626400
//0x71895adf40041c0f8ac8086ee610378882a7ba67495b4efc130f5877dcfde032   SHA 1           //f8b727876f829c0000000012f85ef85c94a1bcfa20a82eca70a5af5420b11bc53a279024ec80b844a9059cbb0000000000000000000000003D7f2E12945987aD44CB7d06CE420aF23948a2900000000000000000000000000000000000000000000000000de0b6b3a764000080830e9e3080820216c0b841b3498379940588c6e7889ad7735ad0987779811872cabbb6a139ff46db5a40fb214a48ec84729167de0ae39a657f63c185c97bf4ab97b819530dd4d40eed920300

