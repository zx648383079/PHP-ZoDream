<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Shop\Domain\Model\OrderModel;
/** @var $this View */
$this->title = '订单详情';

$this->extend('../layouts/header', [
    'header_back' => $this->url('./mobile/order')
]);
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
                    <span class="amount"> x <?=$goods->amount?></span>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="order-amount">
            <p class="text-right">共 <?=count($goods_list)?> 件 合计：<?=$order->goods_amount?></p>
            <p class="line-item"><span>订单号</span> <span><?=$order->series_number?></span> </p>
            <p class="line-item"><span>下单时间</span> <span><?=$order->created_at?></span> </p>
            <?php if($order->pay_at > 0):?>
            <p class="line-item"><span>支付时间</span> <span><?=$this->time($order->pay_at)?></span> </p>
            <?php endif;?>
            <?php if($order->shipping_at > 0):?>
            <p class="line-item"><span>发货时间</span> <span><?=$this->time($order->shipping_at)?></span> </p>
            <?php endif;?>
            <?php if($order->receive_at > 0):?>
            <p class="line-item"><span>签收时间</span> <span><?=$this->time($order->receive_at)?></span> </p>
            <?php endif;?>
            <?php if($order->finish_at > 0):?>
            <p class="line-item"><span>完成时间</span> <span><?=$this->time($order->finish_at)?></span> </p>
            <?php endif;?>
            <hr>
            <p class="line-item"><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
            <p class="line-item"><span>+运费</span> <span><?=$order->shipping_fee?></span> </p>
            <p class="line-item"><span>+支付手续费</span> <span><?=$order->pay_fee?></span> </p>
            <p class="line-item"><span>-优惠</span> <span><?=$order->discount?></span> </p>
            <p class="line-item"><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
        </div>
        <div class="order-footer">
            <div class="order-actions">
                <?php if($order->status == OrderModel::STATUS_UN_PAY):?>
                <a href="<?=$this->url('./mobile/cashier/pay', ['id' => $order->id])?>">支付</a>
                <?php endif;?>
                <?php if($order->status == OrderModel::STATUS_SHIPPED):?>
                <a data-type="del" data-tip="确认收货？" href="<?=$this->url('./mobile/order/receive', ['id' => $order->id])?>">确认收货</a>
                <?php endif;?>
                <?php if($order->status == OrderModel::STATUS_RECEIVED):?>
                <a href="<?=$this->url('./mobile/comment')?>">评价</a>
                <?php endif;?>
                <?php if(in_array($order->status, [OrderModel::STATUS_RECEIVED])):?>
                <a href="">退换货</a>
                <?php endif;?>
                <?php if(in_array($order->status, [OrderModel::STATUS_PAID_UN_SHIP, OrderModel::STATUS_SHIPPED])):?>
                <a href="">申请退款</a>
                <?php endif;?>
                <?php if(in_array($order->status, [OrderModel::STATUS_FINISH])):?>
                <a href="">售后</a>
                <?php endif;?>
                <?php if(in_array($order->status, [OrderModel::STATUS_UN_PAY, OrderModel::STATUS_PAID_UN_SHIP])):?>
                <a data-type="del" data-tip="确认取消此订单？" href="<?=$this->url('./mobile/order/cancel', ['id' => $order->id])?>">取消</a>
                <?php endif;?>
            </div>
        </div>
</div>
