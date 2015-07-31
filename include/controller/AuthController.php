<?php
	class AuthController extends Controller{
		function index(){
			$title="登录";
			$this->show('login');
		}
		
		function login(){
			$email=$_POST['email'];
			$pwd=$_POST['pwd'];
			//$data['success']="成功";
			//$data['errors']['email']='邮箱错误';
			//$data['errors']['pwd']='密码错误';
			
			if($_SESSION['verify'] != $_POST['code']){
				$data['errors']['code']='验证码错误';
				
			}else{
				$_SESSION['user']='1';
				$data['success']="true";
			}
			$this->ajaxJson($data);
		}
		
		function logout()
		{
			$_SESSION['user']=null;
			redirect('/auth-index');
		}
	} 