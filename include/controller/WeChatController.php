<?php
	class WechatController extends Controller{
		function index(){
			$wechat=WeChat();
			//$wechat->valid();
			
			$wechat->getMsg();
			echo $wechat->sendText('你好啊');
			//writeLog($wechat->msg);
			exit;
		}
	} 