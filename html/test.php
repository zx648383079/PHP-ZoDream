<?php
define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', dirname(dirname(__FILE__)));            //定义路径
require_once(APP_DIR.'/vendor/autoload.php');
var_dump(unserialize('a:2:{s:4:"auth";a:2:{s:9:"adminpost";s:1:"0";s:10:"memberpost";s:1:"0";}s:7:"default";a:7:{s:5:"title";a:2:{s:4:"name";s:12:"文章标题";s:4:"show";s:1:"1";}s:5:"thumb";a:2:{s:4:"name";s:9:"缩略图";s:4:"show";s:1:"1";}s:8:"keywords";a:2:{s:4:"name";s:9:"关键字";s:4:"show";s:1:"1";}s:11:"description";a:2:{s:4:"name";s:6:"描述";s:4:"show";s:1:"1";}s:8:"en_title";a:2:{s:4:"name";s:12:"英文标题";s:4:"show";s:1:"1";}s:11:"en_keywords";a:2:{s:4:"name";s:15:"英文关键字";s:4:"show";s:1:"1";}s:14:"en_description";a:2:{s:4:"name";s:12:"英文描述";s:4:"show";s:1:"1";}}}'));