<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="amount-box">
    <div>商品合计:
        <span class="price"><?=floatval($order->goods_amount)?></span>
    </div>
    <div>运费:
        <span class="price"><?=floatval($order->shipping_fee)?></span>
    </div>
    <div>应付总额:
        <span class="price"><?=floatval($order->order_amount)?></span>
    </div>
</div>
<a href="<?=$this->url('./cashier/checkout')?>" class="btn">去付款</a>
<?php if($order->address):?>
<div><?=$order->address->name?> <?=$order->address->tel?></div>
<div><?=$order->address->region_name?> <?=$order->address->address?></div>
<?php endif;?>
<input type="hidden" name="address" value="<?=$order->address ? $order->address->address_id : ''?>">