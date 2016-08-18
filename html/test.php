<?php
define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', dirname(dirname(__FILE__)));            //定义路径
require_once(APP_DIR.'/vendor/autoload.php');
$json = 'access_token=B7EA8CA3EB03FA99943434E6AA61AA94&expires_in=7776000&refresh_token=A706A7D3CEA870B9765319E8A8105156';
if (strpos($json, 'callback') !== false) {
    $leftPos = strpos($json, '(');
    $rightPos = strrpos($json, ')');
    $json  = substr($json, $leftPos + 1, $rightPos - $leftPos -1);
}
$args = [];
parse_str($json, $args);
var_dump($args);
var_dump(\Zodream\Infrastructure\ObjectExpand\StringExpand::getAbsolutePath(APP_DIR.'../../../a.php'));