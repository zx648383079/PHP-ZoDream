<?php
	class HomeController extends Controller{
		function index(){
			Auth::guest()?go("/?c=auth"):"";
			$this->show();
		}
	} 