<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
use Domain\AdminMenu;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.min.css',
        '@zodream-admin.min.css',
        '@dialog.min.css',
        '@admin.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@admin.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, AdminMenu::all(), $this->contents(), $this->title ?? 'ZoDream Admin',  $this->renderPart(dirname(__DIR__).'/navDrop.php')) ?>