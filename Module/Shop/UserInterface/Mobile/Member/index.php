<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '个人中心';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <div class="user-header">
        <div class="avatar">
            <img src="<?=$user->avatar?>">
        </div>
        <div class="name">
            欢迎您，<a href="<?=$this->url('./mobile/member/profile')?>"><?=$user->name?></a>~
        </div>
    </div>
    <div class="menu-grid"><a href="<?=$this->url('./mobile/order')?>" class="item">
            <i class="fa fa-users" aria-hidden="true"></i>
            订单
        </a><a href="<?=$this->url('./mobile/collect')?>" class="item">
            <i class="fa fa-bookmark" aria-hidden="true"></i>
            关注
        </a><a href="message.html" class="item">
            <i class="fa fa-comments" aria-hidden="true"></i>
            留言
        </a><a href="" class="item">
            <i class="fa fa-shield" aria-hidden="true"></i>
            安全
        </a><a href="" class="item">
            <i class="fa fa-cog" aria-hidden="true"></i>
            设置
        </a>
    </div>
    <div class="menu-large">
        <a href="" class="item">
            <i class="fa fa-users" aria-hidden="true"></i>
            好友
        </a><a href="" class="item">
            <i class="fa fa-bookmark" aria-hidden="true"></i>
            关注
        </a><a href="" class="item">
            <i class="fa fa-comments" aria-hidden="true"></i>
            留言
        </a><a href="" class="item">
            <i class="fa fa-shield" aria-hidden="true"></i>
            安全
        </a>
    </div>

    <div class="menu-list">
        <a href="<?=$this->url('./mobile/address')?>">
            <i class="fa fa-users" aria-hidden="true"></i>
            我的收货地址
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="">
            <i class="fa fa-bookmark" aria-hidden="true"></i>
            关注
        </a>
        <a href="">
            <i class="fa fa-comments" aria-hidden="true"></i>
            留言
        </a>
        <a href="help.html">
            <i class="fa fa-gift" aria-hidden="true"></i>
            帮助
        </a>
    </div>
    
</div>

<?php $this->extend('../layouts/navbar');?>