<?php
	/****************************************************
	*定义和启动
	*
	*
	*******************************************************/
	
	
	
	//获取域名或主机地址 
	define('HTTP_HOST', $_SERVER['HTTP_HOST']); #localhost 
	
	define("NWAYSCONF","include/conf/");                          //定义配置目录
	define("NWAYSCLASS","include/class/");                        //定义类目录
	define("NWAYSMODEL","include/model/");                        //定义数据目录
	define("NWAYSMF",".model.php");                        //定义数据文件后缀
	define("NWAYSVIEW","include/view/");                         //定义视图目录
	define("NWAYSCONTROLLER","include/controller/");                   //定义控制器目录
	define("NWAYSCF",".class.php");                       	//定义类文件后缀
	
	include_once(NWAYS."function.php");                  //加载全局方法文件
	
	include_once(NWAYSCLASS."lang".NWAYSCF);
	
	new Lang(getLang());                                 //加载语言包 
	
	require(NWAYSCLASS."pdo".NWAYSCF);                    //加载数据库类文件
	
	if(!file_exists(NWAYSCONF."config.php"))
	{
		redirect('/install.php');
	}
	
	
	session_start();                                  //打开session 因为所有的地方都会先判断session
	
	include_once(NWAYSCLASS."auth".NWAYSCF);                     //加载用户类
	
	
	include_once(NWAYSCONTROLLER."Controller.php");               //加载控制器基类
	
	getCAV();
	
	//$_SERVER["REQUEST_URI"];    //获取网址
	//加载控制器
	/*$con = isset($_GET['c'])?ucfirst($_GET['c']):'Home';
	$name=$con."Controller";
	$view=isset($_GET['v'])?$_GET['v']:'index';
	
	$controllerfile=NWAYSCONTROLLER.$name.".php";
	
	if(is_file($controllerfile))
	{
		require_once($controllerfile);
		if( class_exists($name))
		{
			$controller=new $name;
			if(method_exists($controller,$view))
			{
				$controller->$view();
			}
		}
	}*/