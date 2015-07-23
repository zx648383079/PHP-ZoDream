<?php
	define("ZX","zx/");
	define("ZXC","zx/cof/");
	define("ZXF","zx/class/");
	define("ZXV","zx/view/");
	define("ZXR","zx/controller/");
	define("ZXP",".class.php");
	session_start();                 //打开session 因为所有的地方都会先判断session
	include_once(ZX."function.php");
	include_once(ZXR."Controller.php");
	$con = isset($_GET['c'])?ucfirst($_GET['c']):'Home';
	$name=$con."Controller";
	$view=isset($_GET['v'])?$_GET['v']:'index';
	
	require_once(ZXR.$name.".php");
	$controller=new $name;
	$controller->$view();