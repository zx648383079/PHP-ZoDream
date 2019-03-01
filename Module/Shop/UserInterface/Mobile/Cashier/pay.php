<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '支付订单';

$js = <<<JS
bindPay();
JS;
$this->registerJs($js)
    ->extend('../layouts/header', [
    'header_back' => $this->url('./mobile/order/detail', ['id' => $order->id])
]);
?>

<div class="has-header checkout-box">
    <form action="<?=$this->url('./pay')?>" method="post">
        <div class="money-header">
            <em>￥</em>
            <?=$order->order_amount?>
        </div>
        <div class="checkout-amount">
            <p class="line-item"><span>商品总价</span> <span><?=$order->goods_amount?></span> </p>
            <p class="line-item"><span>运费</span> <span><?=$order->shipping_fee?></span> </p>
            <p class="line-item"><span>订单总价</span> <span><?=$order->order_amount?></span> </p>
        </div>
        <?php if($payment):?>
        <div class="payment-item active" data-id="<?=$payment->id?>">
            <div class="icon">
                <img src="<?=$payment->icon?>" alt="">
            </div>
            <div class="name"><?=$payment->name?></div>
            <div class="status">
                <i class="fa"></i>
            </div>
        </div>
        <?php endif;?>
        <?php if($payment_list):?>
        <div class="payment-hr">选择其他支付方式</div>
        <div class="payment-list">
            <?php foreach($payment_list as $item):?>
            <div class="payment-item" data-id="<?=$item->id?>">
                <div class="icon">
                    <i class="fab fa-weixin"></i>
                </div>
                <div class="name"><?=$item->name?></div>
                <div class="status">
                    <i class="fa"></i>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <?php endif;?>

        <div class="fixed-footer">
            <button class="btn">立即支付</button> 
        </div>
        <input type="hidden" name="order" value="<?=$order->id?>">
        <input type="hidden" name="payment" value="<?=$order->payment_id?>">
    </form>
</div>

