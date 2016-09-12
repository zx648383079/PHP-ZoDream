<?php
define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
$args = md5('ABA7C3D0F459AF49550B50A5F18C805C1473231380008普通版');
var_dump('4e69c57d5c2ced02045e45d60f7bfa1b' == $args);
