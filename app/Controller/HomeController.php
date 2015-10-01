<?php
namespace App\Controller;

use App;
use App\Lib\File\FDir;

class HomeController extends Controller
{
	function indexAction()
	{
		
		$this->send('title','主页');
		$this->show('index');
	}
	
	function aboutAction()
	{
		$this->show('about',array(
			'title' => '关于'
		));
	}
	
	function downloadAction()
	{
		$this->show('download',array(
			'title' => '下载'
		));
	}
} 