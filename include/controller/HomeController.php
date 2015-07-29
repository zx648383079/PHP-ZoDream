<?php
	class HomeController extends Controller{
		function index(){
			Auth::guest()?redirect("/auth-index"):"";
			$this->show();
		}
	} 