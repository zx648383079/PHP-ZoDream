<?php
require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'Api');                            //定义组件名
\Zodream\Service\Web::main()->send();