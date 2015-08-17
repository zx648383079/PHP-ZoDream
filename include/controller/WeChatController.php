<?php
	namespace Controller;
	
	class WechatController extends Controller{
		function index(){
			$wechat=WeChat();
            //$wechat->valid();
            $content=$wechat->getMsg()->Content;
            $wechat->textMsg('你好啊'.$content);
			//writeLog($wechat->msg);
			exit;
		}
	} 