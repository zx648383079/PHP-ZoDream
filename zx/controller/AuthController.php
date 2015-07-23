<?php
	class AuthController extends Controller{
		function index(){
			$this->show('login');
		}
		
		function login(){
			$email=$_POST['email'];
			$pwd=$_POST['pwd'];
			
			
		}
	} 