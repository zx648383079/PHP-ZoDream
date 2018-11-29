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
        '@tool.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@tool.min.js'
    ]);
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
                 './',
                 'fa fa-list'
             ],
             [
                'JS',
                './',
                'fa fa-list'
            ],
            [
                'CSS',
                './',
                'fa fa-list'
            ],
            [
                'JSON',
                './',
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
                 '时间戳',
                 './home/time',
                 'fa fa-clock'
             ],
         ],
         true
     ],
], 'ZoDream Tools') ?>