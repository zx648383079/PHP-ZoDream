<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@prism.css',
    '@zodream.css',
    '@zodream-admin.css',
    '@open.css'
])->registerJsFile([
    '@jquery.min.js',
    '@main.min.js',
    '@open.min.js'
]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '应用管理',
        false,
        'fa fa-th-list',
        [
            [
                '应用列表',
                './platform',
                'fa fa-list'
            ],
            [
                '添加应用',
                './platform/create',
                'fa fa-plus'
            ]
        ],
        true
    ]
], 'ZoDream Open Platform') ?>
