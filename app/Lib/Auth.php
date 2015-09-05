<?php
	namespace App\Lib;
	
	/******************************************************
	*用户类
	*
	*********************************************************/
	use App\Model\UserModel;
	use App\Main;
	
	class Auth{
		
		public static $userModel;
		/*
		 * 判断是否登录
		 *
		 * @access public static
		 *
		 * @return 返回True|False,
		 */
		public static function user(){
			$id = Main::session('user');
			if( !empty($id ) )
			{
				if(empty(self::$userModel))
				{
					$user = new UserModel();
					$user ->id = $id;
					$user ->assignRow('id',$id);
					self::$userModel = $user;
				}
				return self::$userModel;
			}else{
				return false;
			}
		}

		/**
		 * 判断是否是游客
		 *
		 * @return bool
         */
		public static function guest()
		{
			$id = Main::session('user');
			return empty($id);
		}
	}