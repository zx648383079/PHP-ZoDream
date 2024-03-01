<?php

use Zodream\Html\Input;

return [
    'name' => '备份插件',
    'description' => '手动备份',
    'author' => 'zodream',
    'version' => '5.1',
    'entry' => 'ZoDream\Backup\BackupPlugin',
    'autoload' => [
        '.' => 'ZoDream\Backup'
    ],
    'configs' => [
        Input::radio('host', '', ['全部备份', '差异备份']),
        Input::checkbox('', '', ['数据', '资源', '代码'])
    ]
];