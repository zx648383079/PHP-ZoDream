<?php

use Zodream\Service\Application;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Application(APP_DIR);
$app->instance('app.module', 'Install');
$app->listen();