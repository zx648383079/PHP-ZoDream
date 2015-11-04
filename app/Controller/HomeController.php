<?php
namespace App\Controller;

use App;

class HomeController extends Controller
{
	function indexAction()
	{
		$this->show('index', array(
			'title' => '主页'
		));
	}
} 