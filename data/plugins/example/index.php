<?php

use Zodream\Html\Input;

return [
    'name' => '示例插件',
    'description' => '这是一个插件示例',
    'author' => 'zodream',
    'version' => '5.1',
    'entry' => 'ZoDream\Example\ExamplePlugin',
    'autoload' => [
        '.' => 'ZoDream/Example'
    ],
    'events' => [], // 响应事件
    'configs' => [
        Input::tel('tel', '电话号码', true)->value('13000000'),
        Input::text('name', '称呼', true)->value('test')
    ]
];