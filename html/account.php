<?php
/****************************************************
*总入口
*
*
*
*
*******************************************************/
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
if(version_compare(PHP_VERSION, '5.4.0', '<'))  die('require PHP > 5.4.0 !');
define('DEBUG', true);                  //是否开启测试模式
define('APP_ROOT', __DIR__);                     //网站根目录
define('APP_DIR', dirname(APP_ROOT));            //定义路径
define('APP_MODULE', 'Account');                            //定义组件名
require_once(APP_DIR.'/vendor/autoload.php');
Zodream\Service\Application::main();