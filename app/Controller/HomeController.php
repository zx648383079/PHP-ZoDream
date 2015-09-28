<?php
namespace App\Controller;

use App;
use App\Lib\File\FDir;
use App\Model\MethodModel;

class HomeController extends Controller
{
	function indexAction()
	{
		$this->send('title','主页');
		
		$s = App::$request->get('s');
		if(empty($s))
		{
			$this->show('index');			
		}else{
			$model = new MethodModel();
			$data = $model->findList(array(
				"keys like %{$s}%",
				'or' => "title like %{$s}%"
				));
			$this->show('so', array(
					's' => $s,
					'data' => $data
				)
			);			
		}
	}
	
	function methodAction()
	{
		$id = App::$request->get('id');
		$model = new MethodModel();
		$data = $model->findList('id = '.$id);
		$this->show('method',
			array(
				'data' => $data,
				'title' => 'Method'
			)
		);
	}
	
	function createAction()
	{
		if( App::$request->isPost() )
		{
			
		}else {
			$this->show('create',array(
				'title' => 'Create Method'
			));
		}
		
	}
	
	function editAction()
	{
		
	}
} 