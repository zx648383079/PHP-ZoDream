<?php
namespace App\Controller;


class AdminController extends Controller{

	protected $rules = array(
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
	
	function mysql()
	{
		
		$this->show('mysql');
	}
}