<?php
define('APP_DIR', dirname(dirname(__FILE__)));            //定义路径
define('APP_MODULE', 'Home');                            //定义组件名
require_once(APP_DIR.'/vendor/autoload.php');
\Zodream\Infrastructure\Session::getInstance();
var_dump($_SESSION);