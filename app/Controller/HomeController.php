<?php
namespace App\Controller;

use App;
use App\Model\FinanceModel;

class HomeController extends Controller
{
	protected $rules = array(
		'*' => '1'
	);
	
	function indexAction()
	{
		$this->send('title','主页');
		
		$s = App::$request->get('s');
		if(empty($s))
		{
			$this->show('index');			
		}else{
			$kind = new KindModel();
			$kinds = $kind->findList('','id,name');
			$model = new FinanceModel();
			$data = $model->findByKey( $s, App::$request->get('kind') , App::$request->get('page' , 0 ),5);
			$data['s'] = $s;
			$data['kind'] = $kinds;
			$this->show('so', $data );			
		}
	}
	
	function financeAction()
	{
		$this->ajaxJson(array(
			'name' => 'user',
			'msg' => 'name'
		));
	}
	
	function createAction()
	{
		$kind = new KindModel();
		if( App::$request->isPost() )
		{
			$post = App::$request->post('kind,money,happen,mark');
			$error = $this->validata( $post , array(
				'kind' => 'number|required',				
				'money' => 'max:40|required',
				'happen' =>'datetime|required',
			));
			
			if(!is_bool($error))
			{
				$this->send(array(
					'error' => $error,
					'data' => $post
				));
			}else{
				if( $post['kind'] == 1000 && !empty($name = App::$request->post('other') ))
				{
					$post['kind'] = $kind -> fill(array('name' => $name ) );
				}
				
				if($post['kind'] != 1000)
				{
					$model = new MethodModel();
					$id = $model -> fill( $post );
					App::redirect('?v=method&id='.$id);
				}
			}
		}else {
			$kinds = $kind->findList('','id,name');
			$this->show('create',array(
				'title' => 'Create Method',
				'kind' => $kinds
			));
		}
	}
	
	function editAction()
	{
		$id = App::$request->get('id', 1 );
		$model = new MethodModel();
		if( App::$request->isPost() )
		{
			$post = App::$request->post('title,keys,kind,content');
			$error = $this->validata( $post , array(
				'title' => 'max:40|required',
				'keys' =>'max:40|required',
				'kind' => 'number|required',
				'content' => 'required'
			));
			
			if(!is_bool($error))
			{
				$this->send(array(
					'error' => $error,
					'data' => $post
				));
			}else{
				$model -> update( $post , 'id = '.$id);
				App::redirect('?v=method&id='.$id);
			}
		}else {
			$data = $model->findById($id);
			$kind = new KindModel();
			$kinds = $kind->findList('','id,name');
			$this->show('create',array(
				'title' => 'Create Method',
				'data' => $data,
				'kind' => $kinds
			));
		}
	}
} 