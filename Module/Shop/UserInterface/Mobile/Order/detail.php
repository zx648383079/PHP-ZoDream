<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '';

$this->extend('../layouts/header');
?>

<div class="has-header order-box">
    <div class="status-header">
        <i class="fa fa-money-bill"></i>
        <?=$order->status_label?>
    </div>
    <div class="shipping-box">
        <?=$order->shipping_status?>
    </div>
    <div class="address-box">
        <i class="fa fa-map-marker"></i>
        <p>
            <span class="name"><?=$address->name?></span>
            <span class="tel"><?=$address->tel?></span>
        </p>
        <p><?=$address->region_name?> <?=$address->address?></p>
    </div>

    <div class="goods-list">
            <?php foreach($goods_list as $goods):?>
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
            <p class="text-right">共 2 件 合计：<?=$order->goods_amount?></p>
            <p class="line-item"><span>订单号</span> <span><?=$order->id?></span> </p>
            <p class="line-item"><span>下单时间</span> <span><?=$order->created_at?></span> </p>
            <p class="line-item"><span>支付时间</span> <span><?=$order->paid_at?></span> </p>
            <p class="line-item"><span>完成时间</span> <span><?=$order->updated_at?></span> </p>
            
            <p class="line-item"><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
            <p class="line-item"><span>运费</span> <span><?=$order->shipping_fee?></span> </p>
            <p class="line-item"><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
        </div>
        <div class="order-footer">
            <div class="order-actions">
                <a href="">支付</a>
                <a href="">确认收货</a>
                <a href="">评价</a>
                <a href="">退换货</a>
                <a href="">取消</a>
            </div>
        </div>
</div>
