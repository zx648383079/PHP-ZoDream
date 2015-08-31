<?php
	namespace App\Lib;
	
	/******************************************************
	*用户类
	*
	*********************************************************/
	class Auth{
		
		  /**
		 * 公有构造
		 *
		 * @access public
		 *
		 */
		public function __construct()
		{
			if( !isset( $_SESSION ) ){
			    session_start();
			}
		}
		
		/*
		 * 判断是否登录
		 *
		 * @access public static
		 *
		 * @return 返回True|False,
		 */
		public static function user(){
			if( isset( $_SESSION['user'] ) )
			{
				return true;
			}else{
				return false;
			}
		}
	}