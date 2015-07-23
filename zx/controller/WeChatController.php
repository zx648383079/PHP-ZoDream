<?php
	class WeChatController extends Controller{
		function index(){
			$wechat=WeChat();
			$wechat->valid();
		}
	} 