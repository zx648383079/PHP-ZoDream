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
        '@shop_admin.min.css',
        '@legwork.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@legwork.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '服务管理',
         false,
         'fa fa-book',
         [
             [
                 '服务列表',
                 './@admin/service',
                 'fa fa-list'
             ],
             [
                 '添加服务',
                 './@admin/service/create',
                 'fa fa-plus'
             ],
             [
                 '服务分类',
                 './@admin/category',
                 'fa fa-th-large'
             ]
         ],
         true
     ],
     [
        '订单',
        './@admin/order',
        'fa fa-cubes',
    ],
], $this->contents(), 'ZoDream 跑腿管理平台') ?>