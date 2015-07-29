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
			$data['errors']['email']='error';
			$data['errors']['pwd']='error';
			$data['errors']['code']='error';
			$this->ajaxJson($data);
		}
	} 