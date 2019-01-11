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
        '@blog_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        'ueditor/ueditor.config.js',
        'ueditor/ueditor.all.js',
        '@main.min.js',
        '@blog_admin.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './admin',
        'fa fa-home',
    ],
    [
        '帖子管理',
         false,
         'fa fa-book',
         [
             [
                 '帖子列表',
                 './admin/thread',
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
                 './admin/forum',
                 'fa fa-list'
             ],
             [
                 '新增板块',
                 './admin/forum/create',
                 'fa fa-plus'
             ]
         ],
         true
     ],
], 'ZoDream 论坛管理平台') ?>