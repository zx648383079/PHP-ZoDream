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

    <div class="thread-new-box">
        <div class="header">快速发帖</div>
        <div class="title">
            <select name="" id="">
                <option value="">主题分类</option>
            </select>
            <input type="text">
        </div>
        <textarea name=""></textarea>
        <div class="footer">
            <button class="btn">发表回复</button>
        </div>
    </div>
</div>
