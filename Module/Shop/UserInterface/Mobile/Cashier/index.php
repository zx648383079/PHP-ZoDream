<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '结算';

$this->extend('../layouts/header');
?>

<div class="has-header checkout-box">
    <?php if($address):?>
    <div class="address-box">
        <i class="fa fa-map-marker"></i>
        <p>
            <span class="name"><?=$address->name?></span>
            <span class="tel"><?=$address->tel?></span>
        </p>
        <p><?=$address->region->full_name?> <?=$address->address?></p>
        <i class="fa fa-chevron-right"></i>
    </div>
    <?php else:?>
    <div class="address-box empty-address">
        <i class="fa fa-map-marker"></i>
        <h3>请选择地址</h3>
        <i class="fa fa-chevron-right"></i>
    </div>
    <?php endif;?>
    
    
    <div class="goods-list">
        <?php foreach($goods_list as $item):?>
        <div class="goods-item">
            <div class="goods-img">
                <img src="<?=$item->goods->thumb?>" alt="">
            </div>
            <div class="goods-info">
                <h4><?=$item->goods->name?></h4>
                <span class="price"><?=$item->price?></span>
                <span class="amount"> x <?=$item->number?></span>
            </div>
        </div>
        <?php endforeach;?>
    </div>

    <div class="line-item shipping-box">
        <span>配送方式</span>
        <span>请选择</span>
        <i class="fa fa-chevron-right"></i>
    </div>
    <div class="line-item payment-box">
        <span>支付方式</span>
        <span>请选择</span>
        <i class="fa fa-chevron-right"></i>
    </div>

    <div class="checkout-amount">
        <p class="line-item"><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
        <p class="line-item"><span>运费</span> <span><?=$order->shipping_fee?></span> </p>
        <p class="line-item"><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
    </div>
    <div class="checkout-footer">
        <span><?=$order->order_amount?></span>
        <a href="<?=$this->url('./mobile/cashier/checkout')?>" class="btn">立即支付</a>
    </div>
</div>
