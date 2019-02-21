<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '欢迎使用博客管理平台';
?>

<h2>欢迎使用博客管理平台 ！</h2>

<div class="column-item">
    <div class="icon">
        <div class="fa fa-folder"></div>
    </div>
    <div class="content">
        <h3>分类</h3>
        <p><?=$term_count?></p>
    </div>
</div>
<div class="column-item">
    <div class="icon">
        <div class="fa fa-file"></div>
    </div>
    <div class="content">
        <h3>文章</h3>
        <p><?=$blog_count?></p>
    </div>
</div>
<div class="column-item">
    <div class="icon">
        <div class="fa fa-comment"></div>
    </div>
    <div class="content">
        <h3>评论</h3>
        <p><?=$comment_count?></p>
    </div>
</div>
<div class="column-item">
    <div class="icon">
        <div class="fa fa-eye"></div>
    </div>
    <div class="content">
        <h3>浏览量</h3>
        <p><?=$view_count?></p>
    </div>
</div>