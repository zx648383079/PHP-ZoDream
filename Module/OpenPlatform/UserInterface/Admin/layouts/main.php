<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@prism.css',
    '@dialog.css',
    '@zodream.css',
    '@zodream-admin.css',
    '@open.css'
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
        './@admin',
        'fa fa-home',
    ],
    [
        '应用管理',
        false,
        'fa fa-th-list',
        [
            [
                '应用列表',
                './@admin/platform',
                'fa fa-list'
            ],
        ],
        true
    ]
], $this->contents(), 'ZoDream Open Platform') ?>
