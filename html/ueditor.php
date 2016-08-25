<?php
require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'Ueditor');                            //定义组件名
Zodream\Service\Application::main()->send();