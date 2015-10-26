<?php
namespace App\Controller;

use App;
use App\Lib\Auth;
use App\Model\RolesModel;
use App\Model\UserModel;
use App\Lib\Role\RComma;
use App\Model\GroupModel;
use App\Model\SystemModel;
use App\Model\DocumentModel;

class AdminController extends Controller
{

	protected $rules = array(
		'users' => '3',
		'mysql' => '3',
		'*' => '2'
	);
	
	function __construct()
	{
		parent::__construct();
		App::$data['nav'] = 4;
	}
	
	/**
	*后台首页
	*/
	function indexAction(){
		//Auth::user()?"":redirect("/?c=auth");
		$this->send('title','后台');
		$this->show('admin.index');
	}

	/**
	*后台微信操作界面
	*/
	function wechatAction()
	{
		$this->send('title','微信管理');
		$this->show('admin.wechat');
	}
	
	function usersAction()
	{
		$roles = new RolesModel();
		$model = $roles->findList();
		
		$users = new UserModel();
		$id = App::$request->get('id');
		
		if( !empty($id) )
		{
			if( App::$request->isPost() )
			{
				$roles = RComma::compose(App::$request->post('role'));
				$group = new GroupModel();
				$group_id = $group->addRoles($roles);
				$users -> update(
					array(
						'`group`' => $group_id
					),
					array(
						'id = '.$id
					)
				);
			}
			$this->send('id', $id );
		}
		
		
		$user = $users->findWithRoles("u.id <> ".Auth::user()->id,'u.id AS id,u.name AS name,g.roles AS roles');
		
		
		$this->show('admin.users' ,array(
			'roles' => $model,
			'users' => $user,
			'title' => '用户管理'
			));
	}
	
	/******
	数据库语句执行页面
	*/
	function mysqlAction()
	{
		$sql = isset($_POST['sql'])?$_POST['sql']:'';
		$arr = explode('@@',$sql,3);
		$model = array();
		$error = null;
		/*if(!empty($arr[1]))
		{
			$roles = new RolesModel();
			$model = $roles->findByHelper($arr[1]);
			$error = $roles->getError();
		}*/
		
		$this->show('admin.mysql',array(
			'model'=> $model,
			'sql'=> $sql,
			'error' => $error
			));
	}
	
	function systemAction()
	{
		$model = new SystemModel();
		if(App::$request->isPost()) {
			$post = App::$request->post('id 0,page,content');
			$error = $this->validata( $post , array(
				'id' => 'number',	
				'page' => 'max:50|required',
				'content' => 'required'
			));
			
			if(!is_bool($error))
			{
				$this->ajaxJson(array(
					'status' => '10',
					'error' => $error
				));
			}else{
				$data = $model->fillOrUpdate($post);
				$this->ajaxJson(array(
					'status' => '0',
					'data' => $data
				));
			}
		}
		
		$id = App::$request->get('id');
		if(!empty($id)){
			$data = null;
			switch (App::$request->get('mode')) {
				case 'edit':
					$data = $model->findById($id , 'id,page,content');
					break;
				case 'show':
					$model->updateBool('`show`' , 'id = '.$id );
					break;
				case 'delete':
					$model->deleteById( $id );
					break;
				default:
					break;
			}
			$this->ajaxJson(array(
				'status' => '0',
				'data' => $data
			));
		}
		
		$data = $model->findList(null,'id,page,`show`');
		$this->show('admin.system', array(
			'title' => '系统管理',
			'data' => $data
		));
	}
	
	function documentAction()
	{
		$model = new DocumentModel();
		if(App::$request->isPost()) {
			$post = App::$request->post('id 0,pid 0,num 0,title,content');
			$error = $this->validata( $post , array(
				'id' => 'number',
				'pid' => 'number',
				'num' => 'number',		
				'title' => 'max:50|required',
			));
			
			if(!is_bool($error))
			{
				$this->ajaxJson(array(
					'status' => '10',
					'error' => $error
				));
			}else{
				$model->fillOrUpdate($post);
				$this->ajaxJson(array(
					'status' => '0'
				));
			}
		}
		$id = App::$request->get('id');
		if(!empty($id )){
			$data = null;
			switch (App::$request->get('mode')) {
				case 'move':
					$model->move(App::$request->get('id,num'));
					break;
				case 'parent':
					$model->moveParent( App::$request->get('id,pid 0') );
					break;
				case 'delete':
					$model->deleteAllById( $id );
					break;
				default:
					$data = $model->findById( $id, 'id,title,content');
					break;
			}
			$this->ajaxJson(array(
				'status' => '0',
				'data' => $data
			));
		}
		$data = $model->findTitle();
		$this->show('admin.document', array(
			'title' => '文档管理',
			'data' => $data
		));
	}
}