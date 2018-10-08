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
    '@bug.css'
])->registerJsFile([
    '@jquery.min.js',
    '@main.min.js',
    '@bug.min.js'
]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '漏洞管理',
        false,
        'fa fa-th-list',
        [
            [
                '漏洞列表',
                './bug',
                'fa fa-list'
            ],
            [
                '添加漏洞',
                './bug/create',
                'fa fa-plus'
            ]
        ],
        true
    ]
], 'ZoDream Bug') ?>
