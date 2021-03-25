<?php

return [
    'name'       => 'ZoDream',               //应用程序名称
    'title'      => 'ZoDream',
    'host'       => 'zodream.cn',
    'model'      => 'Model',                     //后缀
    'form'       => 'Form',
    'controller' => 'Controller',
    'action'     => 'Action',
    'providers' => [
        \Zodream\Debugger\DebuggerServiceProvider::class,
        \Zodream\Template\ViewServiceProvider::class,
        \Zodream\Database\DatabaseServiceProvider::class,
        // \Service\RouteServiceProvider::class,
    ],
];