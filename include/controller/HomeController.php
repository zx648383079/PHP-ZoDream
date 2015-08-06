<?php
	class HomeController extends Controller{
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			$this->show();
		}
	} 