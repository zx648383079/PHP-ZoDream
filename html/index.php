<?php
require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'Home');                            //定义组件名
if (!\Zodream\Infrastructure\Config::exist()) {
    \Zodream\Domain\Response\Redirect::to('install.php');
}
\Zodream\Service\Application::main()->send();