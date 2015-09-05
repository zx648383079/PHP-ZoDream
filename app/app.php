<?php
	/****************************************************
	*定义和启动
	*
	*
	*******************************************************/

	/************************ ↓包含文件↓ ************************/
	
	require_once(APP_DIR."/vendor/autoload.php");

	
	/************************* ↑包含文件↑ ***********************************/
	
	use App\Main;
	
	/************************ ↓定义常量↓ ************************/
	
	//获取域名或主机地址 
	define('APP_URL', Main::config('app.host')); 
	
	define('APP_API' , isset($_GET['api'])?TRUE:FALSE);    //是否是API模式
	
	
	
	/************************* ↑定义常量↑ ***********************************/
	
	

	
	/************************ ↓执行文件中的方法↓ ************************/

	set_error_handler(array('App\Main','error'));         //自定义错误输出

	register_shutdown_function(array('App\Main','out'));   //程序结束时输出
	
	App\Lib\Lang::setLang();                                //加载语言包 
	
	if( !is_file(APP_DIR."/app/conf/config.php" ) )
	{
		Main::redirect('/install.php');
	}
	

	if(defined('SHORT_URL') && SHORT_URL && isset($_GET['s']))
	{
		Main::short();
	}else{
		Main::getCAV();		
	}
	
	/************************* ↑执行文件中的方法↑ ***********************/
	
	
