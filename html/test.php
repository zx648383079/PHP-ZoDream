<?php
define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', dirname(dirname(__FILE__)));            //定义路径
require_once(APP_DIR.'/vendor/autoload.php');
$ase = new \Zodream\Infrastructure\Security\Aes();
$ase->setKey('12');
$value = $ase->encrypt(null);
var_dump($value);
var_dump($ase->decrypt($value));