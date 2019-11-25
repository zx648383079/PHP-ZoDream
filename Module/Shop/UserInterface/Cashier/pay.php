<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '支付订单';
$js = <<<JS
bindPayTime();
JS;
$this->registerJs($js);
?>
<div class="cashier-page">
    <div class="container">
        <form action="<?=$this->url('./pay')?>" method="post">
            <input type="hidden" name="order" value="<?=$order->id?>">
            <div class="order-pay-tip">
                <div class="primary-tip">
                    <i class="fa fa-check"></i>
                    订单提交成功，请尽快支付
                </div>
                <div>
                    <span>交易号：<?=$order->id?></span>
                    <span>实付:<?=$order->order_amount?></span>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                选择付款方式
                </div>
                <div class="pannel-body payment-box">
                    <?php foreach($payment_list as $item):?>
                        <span class="radio-label">
                            <input type="radio" id="payment<?=$item->id?>" name="payment" value="<?=$item->id?>" <?=$item->id == $order->payment_id ? 'checked' : ''?>>
                            <label for="payment<?=$item->id?>"><?=$item->name?></label>
                        </span>
                    <?php endforeach;?>
                </div>
            </div>
            
            <div class="pay-btn">
                <button class="btn">立即付款</button>
                <div>剩余付款时间 :   
                    <span data-type="countdown" data-end="<?=strtotime($order->created_at) + 3600?>">54分53秒</span>
                </div>
            </div>
        </form>
    </div>
</div>