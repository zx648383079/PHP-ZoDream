<?php
namespace App\Controller;

use App\App;
use App\Lib\File\FDir;
use App\Model\QuoteModel;

class HomeController extends Controller
{
	function index()
	{
		
		$this->send('title','主页');
		$this->show('index');
	}
	
	function blog()
	{
		
		$this->show('blog');
	}
} 