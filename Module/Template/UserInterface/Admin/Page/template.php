<?php
defined('APP_DIR') or exit();

use Module\Template\Domain\VisualEditor\VisualPage;
use Zodream\Template\View;
/** @var $this View */
/** @var VisualPage $page */
$page->renderer()->registerCssFile('@template_edit.css');
?>
<?=$page->render()?>