<?php

use Zodream\Html\Input;

return [
    'name' => '修复网址插件',
    'description' => '更换域名之后需要修复图片路径，使用此插件修复即可',
    'author' => 'zodream',
    'version' => '5.1',
    'entry' => 'ZoDream\RepairHost\RepairHostPlugin',
    'autoload' => [
        '.' => 'ZoDream\RepairHost'
    ],
    'configs' => [
        Input::tel('original', '原域名', true)->value('http://localhost/'),
        Input::text('host', '新域名', true)->value(url('/'))
    ]
];