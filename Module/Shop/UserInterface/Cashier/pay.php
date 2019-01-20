<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
<div class="cashier-page">
    <div class="container">
        <div>选择付款方式</div>
        <div>
            <span>交易号：<?=$order->id?></span>
            <span>实付:<?=$order->order_amount?></span>
        </div>
        <div class="panel">
            <div class="panel-header">
            支付方式
            </div>
            <div class="pannel-body payment-box">
                <?php foreach($payment_list as $item):?>
                    <span class="radio-label">
                        <input type="radio" id="payment<?=$item->id?>" name="payment" value="<?=$item->id?>">
                        <label for="payment<?=$item->id?>"><?=$item->name?></label>
                    </span>
                <?php endforeach;?>
            </div>
        </div>
        <a href="" class="btn">立即付款</a>
        <div>剩余付款时间 :   54分53秒</div>
    </div>
</div>