<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的资料';

$this->extend('../layouts/header');
?>

<div class="has-header">

    <div class="profile-box">
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

    
    <div class="menu-list">
        <a href="<?=$this->url('./mobile/address')?>">
            我的收货地址
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="">
            修改密码
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="<?=$this->url('./mobile/affiliate')?>">
            实名认证
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        
    </div>

    <a class="btn del-btn" href="<?=$this->url('./mobile/member/login')?>">
        退出
    </a>
</div>
