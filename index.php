<?php
/****************************************************
*总入口
*
*
*
*
*******************************************************/

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define("DEBUG", true);                  //是否开启测试模式
define('APP_DIR', dirname(__FILE__).'/app');            //定义路径
define('APP_MODULE', 'App');                            //定义组件名
require_once(dirname(APP_DIR)."/vendor/autoload.php");