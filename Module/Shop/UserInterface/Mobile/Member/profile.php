<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的资料';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <div class="line-item avatar-item">
        <span>头像</span>
        <span>
            <img src="" alt="">
        </span>
        <i class="fa fa-chevron-right"></i>
    </div>
    <div class="line-item">
        <span>昵称</span>
        <span>昵称</span>
        <i class="fa fa-chevron-right"></i>
    </div>
    <div class="line-item">
        <span>性别</span>
        <span>昵称</span>
        <i class="fa fa-chevron-right"></i>
    </div>
    <div class="line-item">
        <span>生日</span>
        <span>昵称</span>
        <i class="fa fa-chevron-right"></i>
    </div>
</div>
