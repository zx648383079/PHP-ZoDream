<?php
	namespace App\Controller;
	
	use App\Controller\Controller;
	
	class HomeController extends Controller{
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			$this->send('title','主页');
			$this->show();
		}
	} 