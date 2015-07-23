<?php
	/****************************************************
	*定义和启动
	*
	*
	*作者：zx
	*更新时间：2015/7/23
	*******************************************************/
	
	
	define("ZXC","zx/cof/");                          //定义配置目录
	define("ZXF","zx/class/");                        //定义类目录
	define("ZXV","zx/view/");                         //定义视图目录
	define("ZXR","zx/controller/");                   //定义控制器目录
	define("ZXP",".class.php");                       //定义类文件后缀
	session_start();                                  //打开session 因为所有的地方都会先判断session
	
	include_once(ZXF."auth".ZXP);                     //加载用户类
	
	include_once(ZX."function.php");                  //加载全局方法文件
	include_once(ZXR."Controller.php");               //加载控制器基类
	//加载控制器
	$con = isset($_GET['c'])?ucfirst($_GET['c']):'Home';
	$name=$con."Controller";
	$view=isset($_GET['v'])?$_GET['v']:'index';
	
	require_once(ZXR.$name.".php");
	$controller=new $name;
	$controller->$view();