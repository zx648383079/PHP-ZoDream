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
        '@book_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@book_admin.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '小说管理',
         false,
         'fa fa-book',
         [
             [
                 '小说列表',
                 './@admin/book',
                 'fa fa-list'
             ],
             [
                 '新建小说',
                 './@admin/book/create',
                 'fa fa-plus'
             ],
             [
                '同步小说',
                './@admin/book/import',
                'fa fa-retweet'
             ]
         ],
         true
     ],
     [
        '分类管理',
         false,
         'fa fa-tags',
         [
             [
                 '分类列表',
                 './@admin/category',
                 'fa fa-list'
             ],
             [
                 '新增分类',
                 './@admin/category/create',
                 'fa fa-plus'
             ]
         ],
     ],
     [
        '作者管理',
         false,
         'fa fa-users',
         [
             [
                 '作者列表',
                 './@admin/author',
                 'fa fa-list'
             ],
             [
                 '新增作者',
                 './@admin/author/create',
                 'fa fa-plus'
             ]
         ],
     ],
], $this->contents(), 'ZoDream 书城管理平台') ?>
