<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的优惠券';
$this->extend('../layouts/header')
?>
<div class="has-header has-footer">
    <div class="tab-header">
        <div class="tab-item active">未使用</div>
        <div class="tab-item">使用记录</div>
        <div class="tab-item">已过期</div>
    </div>

    <div>
        <?php foreach($coupon_list as $item):?>
            <div class="my-coupon-item">
                <div class="price">
                    <em>¥300</em>
                    <p>满168可用</p>
                </div>
                <div class="info">
                    <p><?=$item->name?></p>
                    <div class="time">
                        <span>2018.12.01-2018.12.01</span>
                        <a href="">立即使用</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
        <?php foreach($coupon_list as $item):?>
            <div class="my-coupon-item expired">
                <div class="price">
                    <em>¥300</em>
                    <p>满168可用</p>
                </div>
                <div class="info">
                    <p><?=$item->name?></p>
                    <div class="time">
                        <span>2018.12.01-2018.12.01</span>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>

<footer class="tab-bar">
    <a href="<?=$this->url('./mobile/coupon')?>">
        <i class="fa fa-gift" aria-hidden="true"></i>
        领券
    </a>
    <a href="<?=$this->url('./mobile/coupon/my')?>" class="active">
        <i class="fa fa-user" aria-hidden="true"></i>
        我的优惠券
    </a>
</footer>