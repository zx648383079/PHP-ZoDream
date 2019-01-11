<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js');
?>

<div class="container">
    <div class="thread-list">
        <?php $this->extend('./page');?>
    </div>
</div>
