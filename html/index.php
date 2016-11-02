<?php
require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'Home');                            //定义组件名
if (!\Zodream\Infrastructure\Config::exist()) {
    \Zodream\Infrastructure\Factory::response()->sendRedirect('install.php')->send();
}
\Zodream\Service\Application::main()->send();