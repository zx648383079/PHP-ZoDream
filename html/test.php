<?php
//define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
var_dump(preg_replace('#{([\w_]+)}#i', '(?$1:.*?)', '444'));