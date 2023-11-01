<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;

/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@codemirror.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@tool.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@codemirror.js',
        '@css.js',
        '@xml.js',
        '@htmlmixed.js',
        '@javascript.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@html.parser.min.js',
        '@css.parser.min.js',
        '@main.min.js',
        '@tool.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '编解码',
         false,
         'fa fa-exchange-alt',
         [
             [
                 'URL',
                 './home/url',
                 'fa fa-list'
             ],
             [
                'unicode',
                './home/unicode',
                'fa fa-list'
            ],
            [
                'base64',
                './home/base64',
                'fa fa-list'
            ],
            [
                'ascii',
                './home/ascii',
                'fa fa-list'
            ],
            [
                '进制',
                './home/hex',
                'fa fa-list'
            ],
         ],
         true
     ],
    [
        '加解密',
         false,
         'fa fa-box',
         [
             [
                 'MD5',
                 './home/md5',
                 'fa fa-list'
             ],
             [
                'sha1',
                './home/sha1',
                'fa fa-list'
            ],
            [
                'password_hash',
                './home/password',
            ],
         ],
         true
     ],
     [
        '代码美化',
         false,
         'fa fa-book',
         [
             [
                 'HTML',
                 './home/html',
                 'fa fa-list'
             ],
             [
                'JS',
                './home/js',
                'fa fa-list'
            ],
            [
                'CSS',
                './home/css',
                'fa fa-list'
            ],
            [
                'JSON',
                './home/json',
                'fa fa-list'
            ],
         ],
     ],
     [
        '开发辅助',
         false,
         'fa fa-dharmachakra',
         [
            [
                '颜色RGB',
                './home/color',
                'fa fa-cookie'
            ],
             [
                 '时间戳',
                 './home/time',
                 'fa fa-clock'
             ],
         ],
         true
     ],
], $this->contents(), 'ZoDream Tools') ?>