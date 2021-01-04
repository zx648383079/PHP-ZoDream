<?php
use Zodream\Service\Application;
use Module\Counter\Domain\Middleware\CounterMiddleware;
use Module\SEO\Domain\Middleware\OptionMiddleware;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Application(APP_DIR);
$app->middleware(CounterMiddleware::class, OptionMiddleware::class);
$app->listen();
