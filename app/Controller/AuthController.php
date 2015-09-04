<?php
	namespace App\Controller;

	use App\Main;
	
	class AuthController extends Controller{
		
		protected $rules = array(
			'logout' => '1'	
		);
		
		function index(){
			$post = $_POST;
			if(empty($post))
			{
				$this->show('login',['title' => '登录']);
			}else{
				
				
				Main::redirect('?c=admin');
			}
		}
		
		function qrcode()
		{
			$this->send(array('title'=>'扫扫二维码','img'=>''));
			$this->show('qrcode');
		}
		
		function logout()
		{
			Main::out('gg');
			$_SESSION['user'] = null;
			Main::redirect('/?c=auth');
		}
		
		function register()
		{
			$post = $_POST;
			if(empty($post))
			{
				$this->show('register',['title' => '注册']);
			}else{
				$error = $this->validata($post, array(
					'name' => 'required',
					'email' =>'unique:user|email|required',
					'pwd' => 'confirm:cpwd|min:6|required'
				));
				
				if(!is_bool($error))
				{
					$this->ajaxJson($error);
				}
			}
			
		}
	} 