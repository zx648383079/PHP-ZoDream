<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '订单详情';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <div class="order-header">
        <?=$order->status?>
    </div>
    <div class="shipping-box">
        <?=$order->shipping_status?>
    </div>
    <div class="address-box">
        <p>
            <h4><?=$item->name?></h4>
            <span><?=$item->tel?></span>
        </p>
        <p><?=$item->region->full_name?> <?=$item->address?></p>
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
            <p>共 2 件 合计：<?=$order->goods_amount?></p>
            <p><span>订单号</span> <span><?=$order->id?></span> </p>
            <p><span>下单时间</span> <span><?=$order->created_at?></span> </p>
            <p><span>支付时间</span> <span><?=$order->paid_at?></span> </p>
            <p><span>完成时间</span> <span><?=$order->updated_at?></span> </p>
            
            <p><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
            <p><span>运费</span> <span><?=$order->shipping_fee?></span> </p>
            <p><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
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
