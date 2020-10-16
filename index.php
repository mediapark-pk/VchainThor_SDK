<?php
declare(strict_types=1);

use Comely\DataTypes\Buffer\Base16;
use VchainThor\Math\Integers;
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
//    echo dechex(01);
//    exit();
//    $account = $Vchain->Accounts()->AccountsAddress('0x7567D83b7b8d80ADdCb281A71d54Fc7B3364ffed');
//    echo hexdec($account['balance']);echo '<br>';
//    echo hexdec($account['balance'])/pow(10,18);
//    echo '<br>';
//    echo hexdec($account['energy']);echo '<br>';
//    echo hexdec($account['energy'])/pow(10,18);
//    echo '<br>';
//    var_dump($account);
//    exit();
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
    $tx = new TxBuilder();
    $code = '';
    foreach ($ch2 as $a)
    {
        $code .= $tx::DectoHex($a);
    }

    $int = (int) Integers::Unpack($code)->value();
    $tx->setChainTag(39);
    $tx->setBlockRef($int);
    $tx->setExpiration(18); //fix

    $tx->setClauses(array('to' => '0x3D7f2E12945987aD44CB7d06CE420aF23948a290', "value" => 1, 'data' => array(0)));
    $tx->setClauses(array('to' => '0x3D7f2E12945987aD44CB7d06CE420aF23948a290', "value" => 1, 'data' => array(0)));
    $tx->setClauses(array('to' => '0x3D7f2E12945987aD44CB7d06CE420aF23948a290', "value" => 1, 'data' => array(0)));
    //9fdee3753061cc9033f8bcfb9fd81c18cc137f05
//    $tx->setClauses(array('to' => '0xa1bcfa20a82eca70a5af5420b11bc53a279024ec', "value" => 0, 'data' => array("transfer(address,uint256)",'0x3D7f2E12945987aD44CB7d06CE420aF23948a290','1')),true); //0x0a82c9083a3f16c9837295b5caf21656e84cfda5

    $tx->setGasPriceCoef(0);
    $tx->setGas(958000 );
    $tx->setDependsOn("");
    $tx->setNonce( randomNumber(3) ); //random genrated by code
//    $tx->setNonce(847); //random genrated by code
    $privat_key = 'afc16047f43a67086dd73046ed63851607b943bbc9373bd00f017cde1b42365b';
    $base16_private = new Base16();
    $b_pri = $base16_private->set($privat_key);
    $tx->setPrivate($b_pri);
    echo '<pre>';
    $v = $tx->build_tx();
    var_dump($v);
    echo '<br>';
    $decode = \VchainThor\RLP::Decode($v);
    print_r($decode);

    var_dump($decode[0][9]);
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

//f8b927876f156c0000000012f860df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a76400000080830e9e308082030fc0b84130c2fdad177679f37518f1cd14dd797004f88d0e7bc39eb11dd360a046fd4425630d36b5b3508b86bcd94b0616837e64cec299829b46cda54937793bf5c9747a01
//f8b827876f156e0000000012f860df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a76400000080830e9e308081c6c0b841f55730207f814027a47c2f5a372c8fc292f6ac3296895813950fb5101fbb672d576ef52c70f557eeebe547848a946e7f4808a00760978583fc61a9ba8e53ea6000
//f8b927876f15700000000012f860df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a764000000df943D7f2E12945987aD44CB7d06CE420aF23948a290880de0b6b3a76400000080830e9e3080820124c0b841832232194b835c9fae12d5f9c0d82c9e16e0d8c1e3c63f0d2e225c6f4d41611636d070dd40f56fb58a5abad77f076b1bdd28aa4866842983e08f6fbc09748bd600