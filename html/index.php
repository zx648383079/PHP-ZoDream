<?php
use Zodream\Service\Web;
use Module\Counter\Domain\Middleware\CounterMiddleware;
require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Web(APP_DIR);
$app->middleware(CounterMiddleware::class);
$app->autoResponse();
