<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '支付';

$this->extend('../layouts/header');
?>

<div class="has-header checkout-box">

    <div class="checkout-amount">
        <p class="line-item"><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
        <p class="line-item"><span>运费</span> <span><?=$order->shipping_fee?></span> </p>
        <p class="line-item"><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
    </div>
    <div class="fixed-footer">
        <button class="btn" type="button">立即支付</button> 
    </div>
</div>
