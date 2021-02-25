<?php
//define('DEBUG', true);
//define('APP_DIR', dirname(dirname(__FILE__)));
//require_once(APP_DIR.'/vendor/autoload.php');

use Zodream\Service\Application;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Application(APP_DIR);
