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
        '@contact.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@contact.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '友情链接',
        './@admin/friend_link',
        'fa fa-link'
    ],
    [
        '留言反馈',
        './@admin/feedback',
        'fa fa-cookie'
    ],
    [
        '订阅',
        './@admin/subscribe',
        'fa fa-rss'
    ],
], $this->contents(), 'ZoDream Contact Admin') ?>