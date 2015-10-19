<?php
namespace App\Controller;

use App;
use App\Model\SystemModel;
use App\Model\DocumentModel;

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
		$model = new DocumentModel();
		if(!empty(App::$request->get('id'))){
			$data = $model->findById(App::$request->get('id'), 'id,title,content');
			$this->ajaxJson(array(
				'status' => '0',
				'data' => $data
			));
		}
		$data = $model->findTitle();
		$this->show('document', array(
			'title' => '文档',
			'data' => $data
		));
	}
} 