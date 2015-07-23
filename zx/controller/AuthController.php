<?php
	class AuthController extends Controller{
		function index(){
			$title="登录";
			$this->show('login');
		}
		
		function login(){
			$email=$_POST['email'];
			$pwd=$_POST['pwd'];
			
			
		}
	} 