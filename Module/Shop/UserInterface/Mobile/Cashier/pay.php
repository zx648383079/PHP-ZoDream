<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '应付金额';

$this->extend('../layouts/header', [
    'header_back' => $this->url('./mobile/order/detail', ['id' => $order->id])
]);
?>

<div class="has-header checkout-box">
    <div class="money-header">
        <em>￥</em>
        <?=$order->order_amount?>
    </div>
    <div class="checkout-amount">
        <p class="line-item"><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
        <p class="line-item"><span>运费</span> <span><?=$order->shipping_fee?></span> </p>
        <p class="line-item"><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
    </div>
    <div class="payment-item active">
        <div class="icon">
            <img src="http://zodream.localhost/assets/images/avatar/18.png" alt="">
        </div>
        <div class="name">支付宝</div>
        <div class="status">
            <i class="fa"></i>
        </div>
    </div>
    <div class="payment-hr">选择其他支付方式</div>
    <div class="payment-list">
        <div class="payment-item">
            <div class="icon">
                <i class="fab fa-weixin"></i>
            </div>
            <div class="name">支付宝</div>
            <div class="status">
                <i class="fa"></i>
            </div>
        </div>
        <div class="payment-item">
            <div class="icon">
                <i class="fab fa-paypal"></i>
            </div>
            <div class="name">支付宝</div>
            <div class="status">
                <i class="fa"></i>
            </div>
        </div>
    </div>

    <div class="fixed-footer">
        <button class="btn" type="button">立即支付</button> 
    </div>
</div>

