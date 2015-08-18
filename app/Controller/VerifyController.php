<?php
	namespace App\Controller;
	
	use App\Controller\Controller;
	
	class VerifyController extends Controller{
		function index(){
			
			$code= verify();
			$code->showImg();
			
			$_SESSION['verify']=$code->getCode();
		}
	} 


