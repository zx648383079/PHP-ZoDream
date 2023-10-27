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
        '@bank.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@main.min.js',
        '@bank.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '内容管理',
        false,
        'fa fa-calendar-check',
        [
            [
                '矿场列表',
                './@admin/area',
                'fa fa-list'
            ],
            [
                '矿工种类',
                './@admin/miner',
                'fa fa-list'
            ],
            [
                '住宅种类',
                './@admin/house',
                'fa fa-list'
            ],
        ]
    ],
], $this->contents(), 'ZoDream Bank Admin') ?>
