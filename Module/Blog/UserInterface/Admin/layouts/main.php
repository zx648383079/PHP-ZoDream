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
        '文章管理',
         false,
         'fa fa-book',
         [
             [
                 '文章列表',
                 './admin/blog',
                 'fa fa-list'
             ],
             [
                 '发表文章',
                 './admin/blog/create',
                 'fa fa-plus'
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
                 './admin/term',
                 'fa fa-list'
             ],
             [
                 '新增分类',
                 './admin/term/create',
                 'fa fa-plus'
             ]
         ],
         true
     ],
     [
        '评论',
        './admin/comment',
        'fa fa-comment',
    ],
], 'ZoDream 博客管理平台') ?>