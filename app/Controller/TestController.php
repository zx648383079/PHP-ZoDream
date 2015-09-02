<?php
	namespace App\Controller;
	
	
	class TestController extends Controller{
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			$this->send('title','主页');
			$this->show('test');
		}
	} 