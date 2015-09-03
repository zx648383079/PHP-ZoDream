<?php
	namespace App\Controller;

	use App\Main;
	
	class AuthController extends Controller{
		
		function index(){
			$this->send('title','登录');
			$this->show('login');
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
					'email' =>'email|required',
					'pwd' => 'min:6|required'
				));
				$this->ajaxJson($error);
			}
			
		}
	} 