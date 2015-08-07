<?php
	/****************************************************
	*定义和启动
	*
	*
	*******************************************************/
	
	
	
	
	
	define("NWAYSCONF","include/conf/");                          //定义配置目录
	define("NWAYSCLASS","include/class/");                        //定义类目录
	define("NWAYSVIEW","include/view/");                         //定义视图目录
	define("NWAYSCONTROLLER","include/controller/");                   //定义控制器目录
	define("NWAYSFILE",".class.php");                       //定义类文件后缀
	
	include_once(NWAYS."function.php");                  //加载全局方法文件
	
	if(!file_exists(NWAYSCONF."config.php"))
	{
		redirect('/install.php');
	}
	
	
	session_start();                                  //打开session 因为所有的地方都会先判断session
	
	include_once(NWAYSCLASS."auth".NWAYSFILE);                     //加载用户类
	
	
	include_once(NWAYSCONTROLLER."Controller.php");               //加载控制器基类
	
	//$_SERVER["REQUEST_URI"];    //获取网址
	//加载控制器
	$con = isset($_GET['c'])?ucfirst($_GET['c']):'Home';
	$name=$con."Controller";
	$view=isset($_GET['v'])?$_GET['v']:'index';
	
	require_once(NWAYSCONTROLLER.$name.".php");
	$controller=new $name;
	$controller->$view();