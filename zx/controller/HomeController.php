<?php
	class HomeController extends Controller{
		function index(){
			!auth()?go("/?c=auth"):"";
			
		}
	} 