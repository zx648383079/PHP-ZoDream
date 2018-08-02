<?php
use Tracy\Debugger;
use Zodream\Service\Web;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
//Debugger::enable();
$app = new Web(APP_DIR);
$app->handle()->send();
