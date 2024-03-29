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
        '@code.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@admin.min.js',
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '代码管理',
         false,
         'fa fa-code',
         [
             [
                 '代码列表',
                 './@admin/code',
                 'fa fa-list'
             ],
         ],
         true
     ],
     [
        '评论',
        './@admin/comment',
        'fa fa-comment',
    ],
], $this->contents(), 'ZoDream 代码管理平台') ?>