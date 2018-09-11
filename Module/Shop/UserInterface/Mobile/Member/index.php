<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '个人中心';

$header_btn = <<<HTML
<a class="right" href="/auth/logout">
    <i class="fa fa-sign-out"></i>
</a>
HTML;

$this->extend('../layouts/header', compact('header_btn'));
?>

<div class="has-header has-footer">
    <div class="user-header">
        <div class="avatar">
            <img src="<?=$user ? $user->avatar : '/assets/images/avatar/1.png'?>">
        </div>
        <div class="name">
            欢迎您，<a href="<?=$this->url('./mobile/member/profile')?>"><?=$user ? $user->name : '请登陆'?></a>~
        </div>
    </div>
    <div class="menu-grid"><a href="<?=$this->url('./mobile/order')?>" class="item">
            <i class="fa fa-users" aria-hidden="true"></i>
            订单
        </a><a href="<?=$this->url('./mobile/collect')?>" class="item">
            <i class="fa fa-bookmark" aria-hidden="true"></i>
            关注
        </a><a href="<?=$this->url('./mobile/message')?>" class="item">
            <i class="fa fa-comments" aria-hidden="true"></i>
            消息
        </a><a href="" class="item">
            <i class="fa fa-shield" aria-hidden="true"></i>
            安全
        </a><a href="<?=$this->url('./mobile/member/profile')?>" class="item">
            <i class="fa fa-cog" aria-hidden="true"></i>
            设置
        </a>
    </div>
    <div class="menu-large">
        <a href="" class="item">
            <i class="fa fa-dollar" aria-hidden="true"></i>
            待付款
        </a><a href="" class="item">
            <i class="fa fa-truck" aria-hidden="true"></i>
            待收货
        </a><a href="" class="item">
            <i class="fa fa-comment-o" aria-hidden="true"></i>
            待评价
        </a><a href="" class="item">
            <i class="fa fa-exchange" aria-hidden="true"></i>
           退换货
        </a>
    </div>

    <div class="menu-panel">
        <a href="<?=$this->url('./mobile/address')?>" class="panel-header">
            <i class="fa fa-briefcase" aria-hidden="true"></i>
            我的钱包
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <div class="panel-body">
            <a href="" class="item">
                <span class="menu-item-icon">0
                </span>
                余额
            </a><a href="" class="item">
                <span class="menu-item-icon">0
                </span>
                积分
            </a><a href="" class="item">
                <span class="menu-item-icon">0
                </span>
                红包
            </a><a href="" class="item">
                <span class="menu-item-icon">0
                </span>
                优惠券
            </a>
        </div>
    </div>

    <div class="menu-list">
        <a href="<?=$this->url('./mobile/address')?>">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            我的收货地址
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="">
            <i class="fa fa-history" aria-hidden="true"></i>
            浏览历史
        </a>
        <a href="<?=$this->url('./mobile/affiliate')?>">
            <i class="fa fa-share" aria-hidden="true"></i>
            我的分享
        </a>
        <a href="<?=$this->url('./mobile/article')?>">
            <i class="fa fa-gift" aria-hidden="true"></i>
            帮助
        </a>
    </div>
    
</div>

<?php $this->extend('../layouts/navbar');?>