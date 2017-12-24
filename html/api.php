<?php
use Zodream\Service\Api;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'Api');                            //定义组件名
define('API_VERSION', 'v1');
Api::main()->send();