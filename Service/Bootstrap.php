<?php
/****************************************************
 * 总入口
 *
 *
 *
 *
 *******************************************************/
if (version_compare(PHP_VERSION, '7.0.0', '<'))  {
    die('require PHP > 7.0.0 !');
}
defined('DEBUG') or define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', dirname(__DIR__));            //定义路径
require_once APP_DIR.'/vendor/autoload.php';