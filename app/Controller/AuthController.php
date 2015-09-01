<?php
	namespace App\Controller;
	
	
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
		
		function login(){
			
			$guid = $_POST['guid'];
			
			if(!empty($guid))
			{
				if( $guid == 5 )
				{
					$_SESSION['user'] = '1';
					$data['success'] = "true";
				}else{
					$data['errors']['guid'] = "";
				}
			}else{
				$email = $_POST['email'];
				$pwd=$_POST['pwd'];
				//$data['success']="成功";
				//$data['errors']['email']='邮箱错误';
				//$data['errors']['pwd']='密码错误';
				
				if($_SESSION['verify'] != $_POST['code']){
					$data['errors']['code'] = '验证码错误';
					
				}else{
					$_SESSION['user'] = '1';
					$data['success'] = "true";
				}
			}
			$this->ajaxJson($data);
		}
		
		function logout()
		{
			out('gg');
			$_SESSION['user'] = null;
			redirect('/?c=auth');
		}
	} 