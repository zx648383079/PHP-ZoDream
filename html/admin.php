<?php
use Service\HttpKernel;
use Zodream\Infrastructure\Contracts\Kernel;
use Zodream\Service\Application;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Application(APP_DIR);
$app->instance('app.module', 'Admin');
$app->singleton(
    Kernel::class,
    HttpKernel::class);
$app->listen();