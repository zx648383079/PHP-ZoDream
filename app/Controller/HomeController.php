<?php
namespace App\Controller;

use App;
use App\Model\FinanceModel;

class HomeController extends Controller
{
	protected $rules = array(
		'create' => 'p',
		'*' => '1'
	);
	
	function indexAction()
	{
		$model = new FinanceModel();
		$data = $model->findByKind(App::$request->get('kind') , App::$request->get('page' , 0 ), 5);
		$data['title'] = '主页';
		$this->send($data);
		$this->show('index');
	}
	
	function financeAction()
	{
		$model = new FinanceModel();
		$data = $model->findList('id = '.App::$request->get('id'), 'id,kind,money,happen,mark');
		$this->ajaxJson(array(
			'data' => $data
		));
	}
	
	function createAction()
	{
		$post = App::$request->post('id 0,kind,money,happen,mark');
		$error = $this->validata( $post , array(
			'id' => 'number',
			'kind' => 'number|required',				
			'money' => 'float|required',
			'happen' =>'datetime|required',
		));
		
		if(!is_bool($error))
		{
			$this->ajaxJson(array(
				'status' => '10',
				'error' => $error
			));
		}else{
			$model = new FinanceModel();
			
			if( $post['id'] == 0)
			{
				$model -> fill( $post );
			}else{
				$model -> updateById ( $post, $post['id']);
			}
			$this->ajaxJson(array(
				'status' => '0'
			));
		}
	}
	
	function deleteAction()
	{
		$model = new FinanceModel();
		$data = $model->deleteById(App::$request->get('id'));
		$this->ajaxJson(array(
			'status' => '0'
		));
	}
} 