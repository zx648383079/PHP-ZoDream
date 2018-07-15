<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的订单';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <div class="order-header">
        <a href="" class="active">全部</a>
        <a href="">待支付</a>
        <a href="">待发货</a>
        <a href="">待收货</a>
        <a href="">待评价</a>
        <a href="">已完成</a>
    </div>

    <div class="order-box">
        <?php foreach($order_list as $order):?>
        <div class="order-item">
            <div class="order-header">
                <span><?=$order->id?></span>
                <span class="status"><?=$order->status?></span>
            </div>
            <div class="goods-list">
                <?php foreach($order->goods as $goods):?>
                <div class="goods-item">
                    <div class="goods-img">
                        <img src="<?=$goods->thumb?>" alt="">
                    </div>
                    <div class="goods-info">
                        <h4><?=$goods->name?></h4>
                        <span class="price"><?=$goods->price?></span>
                        <span class="amount"> x <?=$goods->number?></span>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
            <div class="order-amount">
                共 2 件 合计：<?=$order->goods_amount?>
            </div>
            <div class="order-footer">
                <div class="order-actions">
                    <a href="">支付</a>
                    <a href="<?=$this->url('./mobile/order/detail', ['id' => $order->id])?>">详情</a>
                    <a href="">确认收货</a>
                    <a href="">评价</a>
                    <a href="">退换货</a>
                    <a href="">取消</a>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
