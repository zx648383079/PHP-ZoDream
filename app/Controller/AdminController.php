<?php
namespace App\Controller;

use App\App;
use App\Lib\Auth;
use App\Model\RolesModel;
use App\Model\UserModel;
use App\Lib\Role\RComma;
use App\Model\GroupModel;
use App\Model\BlogModel;

class AdminController extends Controller
{

	protected $rules = array(
		'users' => '3',
		'mysql' => '3',
		'*' => '2'
	);

	/**
	*后台首页
	*/
	function index(){
		//Auth::user()?"":redirect("/?c=auth");
		$this->send('title','后台');
		$this->show('admin.index');
	}

	/**
	*后台微信操作界面
	*/
	function wechat()
	{
		$this->send('title','微信管理');
		$this->show('admin.wechat');
	}
	
	function blog()
	{
		if(App::$request->isPost())
		{
			$error = $this->validata(App::$request->post(),array(
				'pid' => 'number|required',
				'title' => 'required'
			));
			$blog = new BlogModel();
			$blog -> fill(App::$request->post('pid 0,title,content'));
		}
		
		$this->send('title','博客管理');
		$this->show('admin.blog');
	}
	
	function users()
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
	function mysql()
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
	
	function about()
	{
		$this->show('admin.about');
	}
}