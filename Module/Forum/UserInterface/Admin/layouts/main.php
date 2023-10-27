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
        '@forum_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@forum_admin.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '帖子管理',
         false,
         'fa fa-book',
         [
             [
                 '帖子列表',
                 './@admin/thread',
                 'fa fa-list'
             ],
         ],
         true
     ],
     [
        '板块管理',
         false,
         'fa fa-tags',
         [
             [
                 '板块列表',
                 './@admin/forum',
                 'fa fa-list'
             ],
             [
                 '新增板块',
                 './@admin/forum/create',
                 'fa fa-plus'
             ]
         ],
         true
     ],
], $this->contents(), 'ZoDream 论坛管理平台') ?>