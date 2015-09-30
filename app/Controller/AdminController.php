<?php
namespace App\Controller;

use App;
use App\Lib\Auth;
use App\Model\RolesModel;
use App\Model\UserModel;
use App\Lib\Role\RComma;
use App\Model\GroupModel;
use App\Model\MethodModel;

class AdminController extends Controller
{

	protected $rules = array(
		'users' => '3',
		'mysql' => '!',
		'*' => '2'
	);

	/**
	*后台首页
	*/
	function indexAction(){
		$this->send('title','后台');
		$this->show('admin.index');
	}
	
	function methodAction()
	{
		$id = App::$request->get('id');
		$model = new MethodModel();
		if(!empty($id))
		{
			$model->deleteById($id);
		}
		$data = $model->findList();
		$this->show('admin.method',
			array(
				'data' => $data,
				'title' => 'Method 管理'
			)
		);
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
			'title' => '数据库',
			'model'=>$model,
			'sql'=>$sql
			));
	}
	
	function aboutAction()
	{
		$this->show('admin.about',array('title' => '关于'));
	}
}