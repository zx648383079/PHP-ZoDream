<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="menu-box">
    <div class="menu-group">
        <div class="menu-name">
        帐号管理
        </div>
        <ul>
            <li class="active"><a>个人中心</a></li>
            <li><a>消息通知</a></li>
            <li><a>帐号信息</a></li>
            <li><a href="<?=$this->url('./address')?>">地址管理</a></li>
            <li><a>帐号安全 </a></li>
            <li><a>我的积分</a></li>
            <li><a>我的收藏</a></li>
            <li><a>我的足迹</a></li>
            <li><a>我的甄选家</a></li>
        </ul>
    </div>
    <div class="menu-group">
        <div class="menu-name">
        交易管理
        </div>
        <ul>
            <li><a href="<?=$this->url('./order')?>">订单管理</a></li>
            <li><a href="<?=$this->url('./bonus')?>">我的红包</a></li>
            <li><a href="<?=$this->url('./coupon')?>">优惠券（0）</a></li>
            <li><a>礼品卡</a></li>
            <li><a>优先购</a></li>
            <li><a>我的众筹</a></li>
            <li><a>服务中心
            </a></li>
            <li><a>售后记录
            </a></li>
            <li><a>价格保护
            </a></li>
            <li><a>帮助中心</a></li>

        </ul>
    </div>
    <div class="menu-group">
        <div class="menu-name">
        服务中心
        </div>
        <ul>
            <li><a>售后记录</a></li>
            <li><a>价格保护</a></li>
            <li><a>帮助中心</a></li>
        </ul>
    </div>
</div>