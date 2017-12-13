<?php
define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
use Zodream\Http\Curl;
use Zodream\Service\Routing\Url;
use Zodream\Module\Gzo\Domain\Opcode\Line;
use Zodream\Service\Factory;
use Zodream\Module\Gzo\Domain\Opcode\DecryptDirectory;
use Zodream\Infrastructure\Security\Des;
use Zodream\Infrastructure\Http\Request;
use Zodream\Domain\Template\Template;

$template = new Template();
echo $template->parse('
{> a=b}
');


//Factory::config()->set([
//    'php_path' => 'D:\phpStudy\php\php-5.6.27-nts\php.exe', // php 执行路径
//    'temp_dir' => 'F:\Desktop\ecm\temp'                     // 缓存文件夹
//]);
//
//(new DecryptDirectory('F:\Desktop\ecm\src', 'F:\Desktop\ecm\dist'))->decode();



/*


$wechat = new \Zodream\ThirdParty\Pay\WeChat();
$wechat->callback();
/*

Config::getInstance()
    ->set('db', [
        'database' => 'sqlcogi',
        'user' => 'root',
        'password' => 'root',
        'prefix' => '',
    ]);




//Record::moveTo((new Query())
//    ->from('brandactivity'), [
//    'fn_1_index' => [
//        'uid' => 1,
//        'mid' => '!news',
//        'catid' => '28',
//        'status' => 9,
//        'inputtime' => time()
//    ],
//    'fn_1_news' => [
//        'id' => 'fn_1_index_id',
//        'catid' => 28,
//        'title' => 'Title',
//        'thumb' => '',
//        'keywords' => '',
//        'description' => 'Summary',
//        'uid' => 1,
//        'author' => '!admin',
//        'status' => 9,
//        'url' => function($item) {
//            return '/index.php?c=show&id='.$item['fn_1_index_id'];
//        },
//        'tableid' => 0,
//        'inputtime' => time(),
//        'updatetime' => time(),
//        'displayorder' => 0,
//    ],
//    'fn_1_news_data_0' => [
//        'id' => 'fn_1_index_id',
//        'uid' => 1,
//        'catid' => 28,
//        'content' => 'Content',
//    ]
////    ->from('product'), [
////    'fn_1_index' => [
////        'uid' => 1,
////        'mid' => '!goods',
////        'catid' => '45',
////        'status' => 9,
////        'inputtime' => time()
////    ],
////    'fn_1_goods' => [
////        'id' => 'fn_1_index_id',
////        'catid' => 45,
////        'title' => 'Title',
////        'keywords' => 'Keywords',
////        'thumb' => 0,
////        'description' => 'Introduction',
////        'url' => '',
////        'uid' => 1,
////        'status' => 9,
////        'tableid' => 0,
////        'author' => '!admin',
////        'inputtime' => time(),
////        'updatetime' => time(),
////        'zuoyong' => 'Slogan',
////        'img' => 0,
////        'h_img' => 0
////    ],
////    'fn_1_goods_data_0' => [
////        'id' => 'fn_1_index_id',
////        'uid' => 1,
////        'catid' => 45,
////        'price' => 'Price',
////        'rongliang' => 'Net',
////        'content' => 'HowToUse',
////        'fname' => 'TitleEn'
////    ],
//]);



//
//function saveSql($sql) {
//    if (strpos($sql, 'INSERT INTO BannerShow (') !== 0
//        && strpos($sql, 'INSERT INTO BrandActivity (') !== 0) {
//        echo '跳过<br>';
//        return;
//    }
//    Command::getInstance()->execute($sql);
//}
//
//$file = new Stream('E:\Desktop\script.sql');
//$file->open('r');
//$sql = '';
//while (!$file->isEnd()) {
//    $line = trim($file->readLine());
//    if (empty($line)) {
//        continue;
//    }
//    if (strpos($line, 'INSERT INTO') === 0) {
//        //$file2->writeLine($sql.';');
//        saveSql($sql);
//        $sql = '';
//    }
//    $sql .= $line.PHP_EOL;
//}
//$file->close();
//echo '完成';

/*

$curl = (new Curl(
    'http://124.118.47.110:8099/member/valid'
));
$curl->post('{"BRANCHID":"ecshop#2","CARDID":"ecs_12123","PWD":"1221","NONCESTR":"WCSFXVwrfZBj3NW5","SIGN":"BRANCHIDCARDIDNONCESTRPWD"}');

die(var_dump($curl));
/*
$wechat = new WeChat([
    'key' => 'qwertyuiop1234567890asdfghjklzxc'
]);
$data = $wechat->declareOrder([
    'appid' => 'wx2e41b6e46fd6dc6b',
    'mch_id' => '1336039401',
    'out_trade_no' => '2017041906395',
    'transaction_id' => '4009732001201704197547362835',
    'customs' => 'GUANGZHOU_ZS',
    'mch_customs_no' => '1105942027'
]);
die(var_dump($data));

/*(new Curl('http://e.hiwein.com/wechat/wx704ceb300fe52cca/message.php?encrypt_type=aes'))
    ->post('<xml>     <ToUserName><![CDATA[gh_73a7f407161d]]></ToUserName>     <Encrypt><![CDATA[GyDyJv5W2DeH4c0o1z5foL9wFT8rxXtV7BjZ4yK19lITOSz74/s+stNlIbGbYPbtFNGgZre2RvaCNofJQmaFPVXM6yhrTAtT8fwewc3n8ITc5yNx8/oxjuzSixKuC2NIQUg6Kaj7F3A9Tm622rZeKM/R6Zc47oU1wDYQa/w0c4is1TCdNnIKcvLLPZBDYmdn/YZBYZzA0H9K43pJa4Qo1G0kThakUktPcgpMeX6ifE6W+g5CqMnqx/w0hh3vqBLuSoQYndEucT6y+fGFU1JI64dz3oMYjyktUJsybYgtv/vnAggA2FjNtm3rnASUsN5o6NuWwC5fK1dwj6SYfjH+T1CPCegZFqVcEojTAqgT2phYsxplsxtMdij5echF8Q+d+zHF7H47QPWMIMa3cjm9PNFCJIT7rdDkJ7AGwI8DiykP8PokoPbQZVJtYEjPXK/jqwVpVJWt8ykNDhYgBi+uZvvD8d7oauU4j+8m50Ke5G6f7VXjFqhymtl4e35oXXW1HDzzp2nlA7+xn/AhkpkPjDO0rzAlTmikma1rdqWdTOqjMQ1caaQX9ehTx13X4Kb84ft8M8Ji8zbb/uOMpoobrg==]]></Encrypt> </xml>');

/*
(new Curl('http://www.koudaiwanzi.com/public/v2/order.notify.wxpay.web'))
    ->post('<xml>     <ToUserName><![CDATA[gh_73a7f407161d]]></ToUserName>     <Encrypt><![CDATA[WI9c9IYwfKurjZu11EK8YS2Byazdg6ytW+PDvpmXeL1NciSVF6J3PTXEtbxcQlsenQcAii3R+oYLY6ixQ/VzGALq/tLwoLQS6BHy/jhjNTx7NwkjaqV6s/A2aOZeTtPrTyT7He0CvI5fpi/d7uqWWqf45qPPuvNZ7UWISnC1OxJ5Xe7DDYyWi5GZJIHs7n4z7LDMffwpiYhYUyIDxm1fJ+CzbWmuVhCT2WvZX6VUNbrgvj2lyOKhWYbNO4HeNN8BF7J+NkT6qNWIJvaz5NYu3IQsQjLlPdNRFFFH99Arc86t/3uSzpUWpFtc3Rp+plF3CCX2fSUco4p5gmpDMFlBtKUGU05NP4FQiGST9PVS06zS4Mr6IBqxlFjcQrZhNjy3tUQYD9kPvDZzFaeD6W+aUIH6GpJbyH22uwwoHw3yV5b9AXvo2/CJpPU8YKz2WOniLmbLMTdzIi4HofYysF8+BOSEQPaMHw5mby5ehGDf5PnGqgi6yi71aqCLvWMbf0QnvH1ZWtUTqV2SseYpveVPKGKwM0bFrMzJm4BtA0F1maigunogj9bTgCGxQTjIaB7QVuCxETOwo0hEV7DHRMYjxw==]]></Encrypt> </xml>');
//(new Curl('http://e.hiwein.com/wechat/wx704ceb300fe52cca/message.php'))
//    ->post('<xml>     <ToUserName><![CDATA[gh_73a7f407161d]]></ToUserName>     <Encrypt><![CDATA[WI9c9IYwfKurjZu11EK8YS2Byazdg6ytW+PDvpmXeL1NciSVF6J3PTXEtbxcQlsenQcAii3R+oYLY6ixQ/VzGALq/tLwoLQS6BHy/jhjNTx7NwkjaqV6s/A2aOZeTtPrTyT7He0CvI5fpi/d7uqWWqf45qPPuvNZ7UWISnC1OxJ5Xe7DDYyWi5GZJIHs7n4z7LDMffwpiYhYUyIDxm1fJ+CzbWmuVhCT2WvZX6VUNbrgvj2lyOKhWYbNO4HeNN8BF7J+NkT6qNWIJvaz5NYu3IQsQjLlPdNRFFFH99Arc86t/3uSzpUWpFtc3Rp+plF3CCX2fSUco4p5gmpDMFlBtKUGU05NP4FQiGST9PVS06zS4Mr6IBqxlFjcQrZhNjy3tUQYD9kPvDZzFaeD6W+aUIH6GpJbyH22uwwoHw3yV5b9AXvo2/CJpPU8YKz2WOniLmbLMTdzIi4HofYysF8+BOSEQPaMHw5mby5ehGDf5PnGqgi6yi71aqCLvWMbf0QnvH1ZWtUTqV2SseYpveVPKGKwM0bFrMzJm4BtA0F1maigunogj9bTgCGxQTjIaB7QVuCxETOwo0hEV7DHRMYjxw==]]></Encrypt> </xml>');
//$aes = new Aes('12345');
/*
function pkcs7Pad($text, $blockSize = 32) {
    $padSize = $blockSize - (strlen($text) % $blockSize);
    return $text . str_repeat(chr($padSize), $padSize);
}

function md($data, $key) {

    $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
    $iv = substr($key, 0, 16);
    //使用自定义的填充方式对明文进行补位填充
    mcrypt_generic_init($module, $key, $iv);
    $data = pkcs7Pad($data);
    //加密
    $data = mcrypt_generic($module, $data);
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);
    return $data;
}

function op($data, $key) {
    $data = pkcs7Pad($data);
    $iv = substr($key, 0, 16);
    return openssl_encrypt($data, 'AES-256-CBC' ,$key, OPENSSL_RAW_DATA, $iv);
}

$key = base64_decode('oScGU3fj8m/tDCyvsbEhwI91M1FcwvQqWuFpPoDHlFk=');
$data = '12312312412421412412412443543534534534';
echo $a1 = base64_encode(md($data, $key)), '<br>', base64_encode(op($data, $key));
/*
class My extends Thread{
    function run(){
        for($i=1;$i<10;$i++){
            echo Thread::getCurrentThreadId() .  "\n";
            sleep(2);     // <------
        }
    }
}

for($i=0;$i<2;$i++){
    $pool[] = new My();
}

foreach($pool as $worker){
    $worker->start();
}
foreach($pool as $worker){
    $worker->join();
}

/*$_POST = array (
    'discount' => '0.00',
    'payment_type' => '1',
    'subject' => '预约看房- ITFR001',
    'trade_no' => '2016111021001004950269824667',
    'buyer_email' => '648383079@qq.com',
    'gmt_create' => '2016-11-10 15:59:06',
    'notify_type' => 'trade_status_sync',
    'quantity' => '1',
    'out_trade_no' => '96',
    'seller_id' => '2088702287963071',
    'notify_time' => '2016-11-10 16:17:51',
    'body' => '第一地产-预约看房的定金',
    'trade_status' => 'TRADE_SUCCESS',
    'is_total_fee_adjust' => 'N',
    'total_fee' => '0.01',
    'gmt_payment' => '2016-11-10 15:59:06',
    'seller_email' => '18621212222',
    'price' => '0.01',
    'buyer_id' => '2088802379732955',
    'notify_id' => 'c868072827ccb0c67f33d538d0f2707nby',
    'use_coupon' => 'N',
    'sign_type' => 'RSA',
    'sign' => 'A6hBh/4p1q/zgwdrIzH1wo2VKLr12lJOkfvtyDRH9bXH6xqI9KHl0QDkJDoQXQ/0iBeHDN3Qd8B+Q3YwzF/fhacEjc+tYMbWUf5Rd3kHEnNa9Di53n097A6gLj5NJv/BC1Tsbw00G7Bs2cFHXfet+e60EklN7BUX9wnClfs/bj0=',
);
$pay = new \Zodream\Domain\ThirdParty\Pay\AliPay([
    'publicKey' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB'
]);
var_dump($pay->callback());
/*
 <xml><appid><![CDATA[wx51fec1bbf0cec9cb]]></appid>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<device_info><![CDATA[web]]></device_info>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[N]]></is_subscribe>
<mch_id><![CDATA[1379809002]]></mch_id>
<nonce_str><![CDATA[Ac8o1XsdVkg5zyO66EGdkCf9yvK3jbUy]]></nonce_str>
<openid><![CDATA[osaKswBu3hwV8jx68WOXTxv1skrM]]></openid>
<out_trade_no><![CDATA[92]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[9D933004067A7C1FD039518EA9E7D9B8]]></sign>
<time_end><![CDATA[20161110131131]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[APP]]></trade_type>
<transaction_id><![CDATA[4005312001201611109292914885]]></transaction_id>
</xml>
 */