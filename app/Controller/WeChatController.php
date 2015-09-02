<?php
	namespace App\Controller;
	
	use App\Lib\WeChat;
	
	class WechatController extends Controller{
		function index(){
            //Wechat::valid();
			$content = '你好啊';
			if (isset(Wechat::getMsg()->Content)) {
				$content .= Wechat::getMsg()->Content;
			}
            Wechat::textMsg($content);
			//writeLog($wechat->msg);
			exit;
		}
	} 