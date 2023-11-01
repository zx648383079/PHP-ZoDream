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
    '@seo.css'
])->registerJsFile([
    '@js.cookie.min.js',
    '@jquery.min.js',
    '@jquery.pjax.min.js',
    '@jquery.dialog.min.js',
    '@jquery.upload.min.js',
    '@main.min.js',
    '@seo.min.js'
])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '设置管理',
        false,
        'fa fa-th-list',
        [
            [
                '基本设置',
                './@admin/setting',
                'fa fa-list'
            ],
        ],
        true
    ],
    [
        '系统管理',
        false,
        'fa fa-cogs',
        [
            [
                '缓存管理',
                './@admin/cache',
                'fa fa-cookie'
            ],
            [
                '生成SiteMap',
                './@admin/home/sitemap',
                'fa fa-map'
            ],
            [
                '数据备份',
                './@admin/sql',
                'fa fa-hdd'
            ],
        ],
        true
    ]
], $this->contents(), $this->title ?? 'ZoDream SEO Admin',  $this->renderPart( $this->getCompleteFile('@root/Admin/navDrop.php') )) ?>
