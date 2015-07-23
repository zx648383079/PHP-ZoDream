<?php
	class WechatController extends Controller{
		function index(){
			$wechat=WeChat();
			$wechat->valid();
		}
	} 