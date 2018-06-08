<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('../layouts/search');
?>

<div class="banner-box">

</div>

<div class="goods-list">
    <div class="item-view">
        <div class="item-img">
            <img src="/assets/images/goods.webp" alt="">
        </div>
        <div class="item-title">
            1233333
        </div>
        <div class="item-actions">
            <span class="item-price">555</span>
            <span>加入购物车</span>
        </div>
    </div>
    <div class="item-view">
        <div class="item-img">
            <img src="/assets/images/goods.webp" alt="">
        </div>
        <div class="item-title">
            1233333
        </div>
        <div class="item-actions">
            <span class="item-price">555</span>
            <span>加入购物车</span>
        </div>
    </div>
</div>

<?php $this->extend('../layouts/navbar');?>