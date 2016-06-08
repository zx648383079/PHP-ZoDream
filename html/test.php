<?php
define('APP_DIR', dirname(__DIR__));            //定义路径
require_once(APP_DIR.'/vendor/autoload.php');
$str = '
 <xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName> 
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[this is a test]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>';
$args = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
var_dump($args);