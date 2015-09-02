<?php
	namespace App\Controller;
	
	
	class AdminController extends Controller{
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			$this->send('title','后台');
			$this->show('admin');
		}
	} 