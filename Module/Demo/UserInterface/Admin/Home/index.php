<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '欢迎使用Demo管理平台';
?>

<h2>欢迎使用Demo管理平台 ！</h2>

<div class="column-item">
    <div class="icon">
        <div class="fa fa-folder"></div>
    </div>
    <div class="content">
        <h3>分类</h3>
        <p><?=$cat_count?></p>
    </div>
</div>
<div class="column-item">
    <div class="icon">
        <div class="fa fa-file"></div>
    </div>
    <div class="content">
        <h3>文章</h3>
        <p><?=$post_count?></p>
    </div>
</div>
<div class="column-item">
    <div class="icon">
        <div class="fa fa-download"></div>
    </div>
    <div class="content">
        <h3>下载</h3>
        <p><?=$download_count?></p>
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