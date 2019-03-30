<?php

use Zodream\Service\Api;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Api(APP_DIR, 'Api');
$app->autoResponse();