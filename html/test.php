<?php
define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', dirname(dirname(__FILE__)));            //定义路径
require_once(APP_DIR.'/vendor/autoload.php');
$i = 0;
preg_replace_callback('/\d/', function ($matches) use (&$i) {
    $i ++;
    var_dump($i);
    var_dump($matches);
    return '';
}, '1234567');
