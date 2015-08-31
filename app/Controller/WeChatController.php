<?php
	namespace App\Controller;
	
	use App\Lib\WeChat;
	
	class WechatController extends Controller{
		function index(){
            //Wechat::valid();
            $content = Wechat::getMsg()->Content;
            Wechat::textMsg('你好啊'.$content);
			//writeLog($wechat->msg);
			exit;
		}
	} 