<?php
	namespace App\Controller;
	
	
	class ModelController extends Controller{
		function index(){
			$user=pdo("user");
			$this->ajaxJson($user->select());
		}
	}