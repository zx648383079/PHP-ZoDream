<?php
//define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
var_dump(json_encode(array(
    'gg' => false,
    'aa' => true
)));
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