<?php
use Tracy\Debugger;
use Zodream\Service\Web;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Web(APP_DIR, 'Admin');
$app->handle()->send();