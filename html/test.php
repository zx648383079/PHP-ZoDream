<?php
require_once(dirname(__DIR__).'/vendor/autoload.php');
echo \Zodream\Infrastructure\ObjectExpand\PinYin::encode('振', 'all');

var_dump(1<<8);