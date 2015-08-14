<?php
	/****************************************************
	*定义和启动
	*
	*
	*******************************************************/
	
	
	
	/************************ ↓定义常量↓ ************************/
	
	//获取域名或主机地址 
	define('HTTP_HOST', $_SERVER['HTTP_HOST']); #localhost 
	
	define("NWAYSCONF","include/conf/");                          //定义配置目录
	define("NWAYSCLASS","include/class/");                        //定义类目录
	define("NWAYSMODEL","include/model/");                        //定义数据目录
	define("NWAYSMF",".model.php");                        //定义数据文件后缀
	define("NWAYSVIEW","include/view/");                         //定义视图目录
	define("NWAYSCONTROLLER","include/controller/");                   //定义控制器目录
	define("NWAYSCF",".class.php");                       	//定义类文件后缀
	
	/************************* ↑定义常量↑ ***********************************/
	
	
	/************************ ↓包含文件↓ ************************/
	
	include_once(NWAYS."function.php");                  //加载全局方法文件
	
	include_once(NWAYSCLASS."lang".NWAYSCF);
	
	require(NWAYSCLASS."pdo".NWAYSCF);                    //加载数据库类文件
	
	include_once(NWAYSCLASS."lang".NWAYSCF);                    //加载语言库类文件
	
	include_once(NWAYSCLASS."auth".NWAYSCF);                     //加载用户类
	
	include_once(NWAYSCONTROLLER."Controller.php");               //加载控制器基类
	
	/************************* ↑包含文件↑ ***********************************/
	
	/************************ ↓执行文件中的方法↓ ************************/
	
	new Lang(getLang());                                 //加载语言包 
	
	if(!is_file(NWAYSCONF."config.php"))
	{
		redirect('/install.php');
	}
	
	session_start();                                  //打开session 因为所有的地方都会先判断session
	getCAV();
	
	/************************* ↑执行文件中的方法↑ ***********************/
	
	
