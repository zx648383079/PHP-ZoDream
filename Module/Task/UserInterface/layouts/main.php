<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@task.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@task.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '任务管理',
        false,
        'fa fa-business-time',
        [
            [
                '任务列表',
                './task',
                'fa fa-list'
            ],
            [
                '新建任务',
                './task/create',
                'fa fa-plus'
            ]
        ],
        true
    ]
], $content, 'ZoDream Task') ?>
