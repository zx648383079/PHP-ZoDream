<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */
$this->title = '结算';

$js = <<<JS
bindMobileCashier();
JS;

$this->extend('../layouts/header')
    ->registerCssFile('@dialog-select.min.css')
    ->registerJsFile('@jquery.selectbox.min.js')
    ->registerJs($js);
?>

<div class="has-header has-footer checkout-box">
    <?=Form::open('./mobile/cashier/checkout')?>
        <?php if($address):?>
        <div class="address-box">
            <i class="fa fa-map-marker"></i>
            <p>
                <a href="<?=$this->url('./mobile/address', ['selected' => $address->id])?>">
                    <span class="name"><?=$address->name?></span>
                    <span class="tel"><?=$address->tel?></span>
                </a>
            </p>
            <p><?=$address->region->full_name?> <?=$address->address?></p>
            <i class="fa fa-chevron-right"></i>
        </div>
        <?php else:?>
        <div class="address-box empty-address">
            <i class="fa fa-map-marker"></i>
            <h3>
                <a href="<?=$this->url('./mobile/address')?>">请选择地址</a>
            </h3>
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
            <span>
                <select name="shipping">
                    <option value="0">请选择</option>
                    <?php foreach($shipping_list as $item):?>
                    <option value="<?=$item->id?>"><?=$item->name?></option>
                    <?php endforeach;?>
                </select>
            </span>
        </div>
        <div class="line-item payment-box">
            <span>支付方式</span>
            <span>
                <select name="payment">
                    <option value="0">请选择</option>
                    <?php foreach($payment_list as $item):?>
                    <option value="<?=$item->id?>"><?=$item->name?></option>
                    <?php endforeach;?>
                </select>
            </span>
        </div>

        <div class="checkout-amount">
            <p class="line-item"><span>商品总价</span> <span data-key="goods_amount"><?=$order->goods_amount?></span> </p>
            <p class="line-item"><span>+运费</span> <span data-key="shipping_fee"><?=$order->shipping_fee?></span> </p>
            <p class="line-item"><span>+支付手续费</span> <span data-key="pay_fee"><?=$order->pay_fee?></span> </p>
            <p class="line-item"><span>-优惠</span> <span data-key="discount"><?=$order->discount?></span> </p>
            <p class="line-item"><span>订单总价</span> <span data-key="order_amount"><?=$order->order_amount?></span> </p>
        </div>
        <div class="checkout-footer">
            <span data-key="order_amount"><?=$order->order_amount?></span>
            <a href="<?=$this->url('./mobile/cashier/checkout')?>" class="btn">立即支付</a>
        </div>
        <input type="hidden" name="address" value="<?=$address ? $address->id : ''?>">
        <input type="hidden" name="cart" value="<?=$this->text($cart)?>">
        <input type="hidden" name="type" value="<?=$type?>">
    <?=Form::close()?>
</div>
