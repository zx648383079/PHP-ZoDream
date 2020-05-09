<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '族谱';
$this->registerCssFile('@family.css');
?>
<div class="book-box">
    <?php $this->extend('./page');?>
</div>