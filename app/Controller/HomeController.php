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
				"`keys` like %{$s}%",
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
			$post = App::$request->post('title,keys,content');
			$error = $this->validata( $post , array(
				'title' => 'max:40|required',
				'keys' =>'max:40|required',
				'content' => 'required'
			));
			
			if(!is_bool($error))
			{
				$this->send(array(
					'error' => $error,
					'data' => $post
				));
			}else{
				$model = new MethodModel();
				$id = $model -> fill( $post );
				App::redirect('?v=method&id='.$id);
			}
		}else {
			$this->show('create',array(
				'title' => 'Create Method'
			));
		}
		
	}
	
	function editAction()
	{
		if( App::$request->isPost() )
		{
			
		}else {
			$this->show('create',array(
				'title' => 'Edit Method'
			));
		}
	}
} 