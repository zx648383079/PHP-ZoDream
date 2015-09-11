<?php
	/****************************************************
	*总入口
	*
	*
	*
	*
	*******************************************************/
	
	define("DEBUG", true);                  //是否开启测试模式


	define('APP_DIR', dirname(__FILE__));
	require_once(APP_DIR."/vendor/autoload.php");
	App\App::main();