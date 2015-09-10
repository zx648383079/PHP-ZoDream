<?php
namespace App\Controller;

use App\Model\RolesModel;

class AdminController extends Controller{

	protected $rules = array(
		'mysql' => '1',
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
		$this->show('users' ,array('roles'=>$model));
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
}