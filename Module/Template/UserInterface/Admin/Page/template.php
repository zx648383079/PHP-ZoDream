<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$page->getFactory()->registerCssFile('@template_edit.css');
?>
<?=$page->template()?>