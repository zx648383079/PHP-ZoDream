<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '结算';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <div class="address-box">
        <p>
            <h4><?=$item->name?></h4>
            <span><?=$item->tel?></span>
        </p>
        <p><?=$item->region->full_name?> <?=$item->address?></p>
    </div>
    
    <div class="goods-list">
        <?php foreach($order->goods as $goods):?>
        <div class="goods-item">
            <div class="goods-img">
                <img src="<?=$item->goods->thumb?>" alt="">
            </div>
            <div class="goods-info">
                <h4><?=$item->goods->name?></h4>
                <span class="price"><?=$item->price?></span>
                <span class="amount"> x <?=$item->amount?></span>
            </div>
        </div>
        <?php endforeach;?>
    </div>

    <div class="shipping-box">
        <span>配送方式</span>
        <select name=""></select>
    </div>
    <div class="payment-box">
        <span>支付方式</span>
        <select name=""></select>
    </div>

    <div class="order-amount">
        <p><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
        <p><span>运费</span> <span><?=$order->shipping_fee?></span> </p>
        <p><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
    </div>
    <div class="order-footer">
        <span>0</span>
        <a href="" class="btn">立即支付</a>
    </div>
</div>
