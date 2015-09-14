<?php
namespace App\Controller;

use App\Lib\Auth;
use App\Model\RolesModel;
use App\Model\UserModel;

class AdminController extends Controller{

	protected $rules = array(
		'users' => '99',
		'mysql' => '99',
		'*' => '2'
	);

	/**
		*后台首页
		*/
	function index(){
		//Auth::user()?"":redirect("/?c=auth");
		$this->send('title','后台');
		$this->show('admin');
	}

	/**
		*后台微信操作界面
		*/
	function wechat()
	{
		$this->send('title','微信管理');
		$this->show('wechat');
	}
	
	function users()
	{
		$roles = new RolesModel();
		$model = $roles->findList();
		$users = new UserModel();
		$user = $users ->findList("id <> {Auth::user()->id}",'id,name');
		
		$this->show('users' ,array(
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
		
		$this->show('mysql',array(
			'model'=>$model,
			'sql'=>$sql
			));
	}
	
	function about()
	{
		$this->show('about');
	}
}