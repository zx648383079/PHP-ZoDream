<?php
//define('DEBUG', true);
//define('APP_DIR', dirname(dirname(__FILE__)));
//require_once(APP_DIR.'/vendor/autoload.php');

use Zodream\Service\Application;
use Zodream\Service\Http\Kernel;
use Zodream\Debugger\Domain\Log;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Application(APP_DIR);
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

