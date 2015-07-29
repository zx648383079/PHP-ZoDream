<?php
	/******************************************************
	*用户类
	*
	*********************************************************/
	class Auth{
		
		public function __construct()
		{
			if(!isset($_SESSION)){
			    session_start();
			}
		}
		//判断是否登录
		public static function user(){
			if(isset($_SESSION['user']))
			{
				return true;
			}else{
				return false;
			}
		}
		//判断是否是游客
		public static function guest()
		{
			if(isset($_SESSION['user']))
			{
				return false;
			}else{
				return true;
			}
		}
	}