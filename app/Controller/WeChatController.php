<?php
namespace App\Controller;

use App\Lib\WeChat;
use App\Model\WechatModel;

class WechatController extends Controller{
	function index(){
		//Wechat::valid();
		$content = '你好啊';
		if (isset(Wechat::getMsg()->Content)) {
			$content .= Wechat::getMsg()->Content;
		}
		
		$wechat = new WechatModel();
		$wechat->add(WeChat::getAppMsg());
		
		Wechat::textMsg($content);
		//writeLog($wechat->msg);
		exit;
	}
} 