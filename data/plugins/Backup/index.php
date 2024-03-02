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
        Input::radio('type', '备份方式',
            Input::optionItems([
                '全部备份',
                '差异备份'
            ])
        ),
        Input::checkbox('source', '备份内容', Input::optionItems(['数据', '资源', '代码']))
    ]
];