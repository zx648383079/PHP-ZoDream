<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;

/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.min.css',
        '@zodream-admin.min.css',
        '@dialog.min.css',
        '@bank.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
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
        '银行管理',
        false,
        'fa fa-calendar-check',
        [
            [
                '产品管理',
                './@admin/product',
                'fa fa-list'
            ],
            [
                '投资记录',
                './@admin/log',
                'fa fa-list'
            ],
        ]
    ],
], $this->contents(), 'ZoDream Bank Admin') ?>
