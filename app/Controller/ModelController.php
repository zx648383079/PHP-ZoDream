<?php
	namespace App\Controller;
	
	use App\Controller\Controller;
	
	class ModelController extends Controller{
		function index(){
			$user=pdo("user");
			$this->ajaxJson($user->select());
		}
	}