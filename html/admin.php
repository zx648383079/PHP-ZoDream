<?php
use Tracy\Debugger;
use Zodream\Service\Web;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
define('APP_MODULE', 'Admin');                            //定义组件名
Debugger::enable();
Web::main()->send();