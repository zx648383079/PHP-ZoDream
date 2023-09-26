<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
use Domain\AdminMenu;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@admin.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, AdminMenu::all(), $content, $this->title ?? 'ZoDream Admin') ?>