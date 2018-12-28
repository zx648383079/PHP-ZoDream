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
        '@admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@admin.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '系统管理',
        false,
        'fa fa-cogs',
        [
            [
                '基本设置',
                './',
                'fa fa-cog'
            ],
            [
                '清除缓存',
                './cache',
                'fa fa-trash'
            ],
            [
                '生成SiteMap',
                './sitmap',
                'fa fa-map'
            ]
        ],
        true
    ]
], 'ZoDream Admin') ?>