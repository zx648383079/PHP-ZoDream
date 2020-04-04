<?php
use Zodream\Service\Web;
use Module\Counter\Domain\Middleware\CounterMiddleware;
use Module\SEO\Domain\Middleware\OptionMiddleware;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Web(APP_DIR);
$app->middleware(CounterMiddleware::class, OptionMiddleware::class);
$app->autoResponse();
