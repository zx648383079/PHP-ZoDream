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
        '@datetimer.min.css',
        '@family.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@family.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '族谱管理',
         false,
         'fa fa-book',
         [
             [
                 '族谱列表',
                 './@admin/clan',
                 'fa fa-list'
             ],
             [
                 '新增族谱',
                 './@admin/clan/create',
                 'fa fa-plus'
             ],
         ],
         true
     ],
    [
        '族人管理',
         false,
         'fa fa-user',
         [
             [
                 '族人列表',
                 './@admin/family',
                 'fa fa-list'
             ],
             [
                 '新增族人',
                 './@admin/family/create',
                 'fa fa-plus'
             ]
         ],
         true
     ],
     
], $this->contents(), 'ZoDream 族谱管理平台') ?>