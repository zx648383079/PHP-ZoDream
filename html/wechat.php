<?php
require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'WeChat');                            //定义组件名
Zodream\Service\Application::main()->send();