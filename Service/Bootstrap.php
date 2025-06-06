<?php
/****************************************************
 * 总入口
 *
 *
 *
 *
 *******************************************************/
if (version_compare(PHP_VERSION, '8.2.0', '<'))  {
    die('require PHP > 8.2.0 !');
}
defined('DEBUG') or define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));            //定义路径
require_once __DIR__.'/../vendor/autoload.php';