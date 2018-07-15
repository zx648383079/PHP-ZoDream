<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '文章内容';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <div class="article-title">124313232</div>
    <div class="article-status">
        <span class="author"><i class="fa fa-edit"></i><b>admin</b></span>
        <span class="category"><i class="fa fa-bookmark"></i><b>其他</b></span>
        <span class="comment"><i class="fa fa-comments"></i><b>0</b></span>
        <span class="agree"><i class="fa fa-thumbs-o-up"></i><b>0</b></span>
        <span class="click"><i class="fa fa-eye"></i><b>31</b></span>
    </div>
    <div class="article-content">
        123123131312
    </div>
</div>
