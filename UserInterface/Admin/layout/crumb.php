<?php
defined('APP_DIR') || die();
/** @var $this \Zodream\Domain\View\View */
use Zodream\Html\Bootstrap\BreadcrumbWidget;
$title = explode('-', $this->title);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?=$title[1]?><small><?=$title[0]?></small>
    </h1>
    <?=BreadcrumbWidget::show([
        'links' => $links
    ])?>
</section>