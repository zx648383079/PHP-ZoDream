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
		if(!empty($arr[1]))
		{
			$roles = new RolesModel();
			$model = $roles->execute($arr[1])->getList();
		}
		
		$this->show('admin.mysql',array(
			'model'=>$model,
			'sql'=>$sql
			));
	}
	
	function systemAction()
	{
		$model = new SystemModel();
		$data = $model->findList(null,'id,page');
		$this->show('admin.system', array(
			'title' => '系统管理',
			'date' => $data
		));
	}
	
	function documentAction()
	{
		$model = new DocumentModel();
		if(App::$request->isPost()) {
			
		}
		if(empty(App::$request->get('id'))){
			
		}
		
		$data = $model->findList(null,'id,page');
		$this->show('admin.document', array(
			'title' => '文档管理',
			'date' => $data
		));
	}
}