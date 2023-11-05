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
    '@js.cookie.min.js',
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
        '我的站点',
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
            ],
        ],
        true,
    ],
    [
        '模板市场',
        false,
        'fa fa-book',
        [
            [
                '组件市场',
                './@admin/market',
                'fa fa-cart-plus'
            ],
            [
                '共享站点',
                './@admin/market/site',
                'fa fa-clone'
            ],
            [
                '上传组件',
                './@admin/market/my',
                'fa fa-cloud-upload-alt'
            ],
            [
                '分类管理',
                './@admin/category',
                'fa fa-bookmark',
                [],
                false,
                false,
                auth()->user()->isAdministrator()
            ],
            // [
            //     '刷新主题',
            //     './@admin/theme/refresh',
            //     'fa fa-retweet'
            // ]
        ],
        true
    ],
], $this->contents(), $this->title ?? 'ZoDream Template Admin') ?>