<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@prism.min.css',
    '@dialog.min.css',
    '@zodream.min.css',
    '@zodream-admin.min.css',
    '@open.min.css'
])->registerJsFile([
    '@js.cookie.min.js',
    '@jquery.min.js',
    '@jquery.pjax.min.js',
    '@jquery.dialog.min.js',
    '@main.min.js',
    '@admin.min.js',
    '@open.min.js'
])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '授权管理',
        './authorize',
        'fa fa-user-cog',
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
], $this->contents(), 'ZoDream Open Platform') ?>
