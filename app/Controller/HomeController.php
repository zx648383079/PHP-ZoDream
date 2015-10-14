<?php
namespace App\Controller;

use App;
use App\Model\SystemModel;

class HomeController extends Controller
{
	function indexAction()
	{
		$model = new SystemModel();
		$data = $model->findByPage('home');
		$this->show('index', array(
			'title' => '主页',
			'data' => $data
		));
	}
	
	function aboutAction()
	{
		$model = new SystemModel();
		$data = $model->findByPage('home');
		$this->show('about', array(
			'title' => '关于',
			'data' => $data
		));
	}
	
	function downloadAction()
	{
		$model = new SystemModel();
		$data = $model->findByPage('home');
		$this->show('download', array(
			'title' => '下载',
			'data' => $data
		));
	}
	
	function documentAction()
	{
		$this->show('document', array(
			'title' => '文档'
		));
	}
} 