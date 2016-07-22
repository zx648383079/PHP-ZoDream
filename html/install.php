<?php
/****************************************************
*总入口
*
*
*
*
*******************************************************/

if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');
define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', dirname(dirname(__FILE__)));            //定义路径
define('APP_MODULE', 'Install');                            //定义组件名
require_once(APP_DIR.'/vendor/autoload.php');
Zodream\Service\Application::main()->send();