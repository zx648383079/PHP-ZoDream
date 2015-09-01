<?php
	/****************************************************
	*总入口
	*
	*
	*
	*
	*******************************************************/
	
	define("DEBUG", true);                  //是否开启测试模式
	define('SHORT_URL', true);              //是否开启短链接
	
	define('APP_DIR', dirname(__FILE__));
	include_once(APP_DIR."/app/app.php");           //入口