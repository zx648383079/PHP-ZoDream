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
    '@template.css'
])->registerJsFile([
    '@jquery.min.js',
    '@jquery.dialog.min.js',
    '@jquery.datetimer.min.js',
    '@main.min.js',
    '@admin.min.js',
    '@template.min.js'
])->registerJs(sprintf('var BASE_URI="%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>
<?= Layout::main($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '页面管理',
        false,
        'fa fa-columns',
        [
            [
                '站点列表',
                './@admin/site',
                'fa fa-list'
            ],
            [
                '新增站点',
                './@admin/site/create',
                'fa fa-plus'
            ]
        ],
        true,
    ],
    [
        '模板管理',
        false,
        'fa fa-book',
        [
            [
                '主题列表',
                './@admin/theme',
                'fa fa-list'
            ],
            [
                '刷新主题',
                './@admin/theme/refresh',
                'fa fa-retweet'
            ]
        ],
    ],
], $this->contents(), 'ZoDream Template Admin') ?>