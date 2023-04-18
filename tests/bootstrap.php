<?php

use Zodream\Service\Application;
use Zodream\Service\Console\Kernel;

defined('DEBUG') or define('DEBUG', true);                  //是否开启测试模式
define('APP_DIR', dirname(__DIR__));            //定义路径
require APP_DIR . '/vendor/autoload.php';

$app = new Application(APP_DIR);
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();