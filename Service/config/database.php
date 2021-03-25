<?php

return [
    'connections' => [
        'driver'   => \Zodream\Database\Engine\Pdo::class,
        'type'     => 'mysql',
        'port'     => '3306',						//端口
        'host' => 'localhost',
        'database' => 'zodream',
        'user' => 'root',
        'password' => '',
        'prefix' => '',
        'encoding' => 'utf8mb4',					//编码
        'allowCache' => true,                   //是否开启查询缓存
        'cacheLife' => 3600,                      //缓存时间
        'persistent' => false                   //使用持久化连接
    ]
];