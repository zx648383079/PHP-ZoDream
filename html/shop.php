<?php
require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'Shop');                            //定义组件名
\Zodream\Service\Web::main()->send();