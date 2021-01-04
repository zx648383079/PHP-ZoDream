<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */
$this->title = '结算';
$js = <<<JS
bindCashier();
JS;
$this->registerJs($js)
    ->registerJsFile('@jquery.multi-select.min.js');
?>

<div class="cashier-page">
    <div class="container">
        <div class="panel">
            <div class="panel-header">
            收货信息
            </div>
            <div class="panel-body">
                <?php if($address):?>
                   <?php $this->extend('./address');?>
                <?php else:?>
                    <?php $this->extend('./addressEdit');?>
                <?php endif;?>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header order-goods-header">
               <div>商品信息</div> 
               <div>单价</div>
               <div>数量</div>
               <div>小计</div>
               <div>实付</div>
            </div>
            <div class="panel-body">
                <?php foreach($goods_list as $item):?>
                <div class="order-goods-item">
                    <div class="goods-info">
                        <div class="thumb">
                            <img src="<?=$item->goods->thumb?>" alt="">
                        </div>
                        <div>
                            <div class="name"><?=$item->goods->name?></div>
                            <div class="attr">白色</div>
                        </div>
                    </div> 
                    <div><?=$item->price?></div>
                    <div><?=$item->number?></div>
                    <div><?=$item->total?></div>
                    <div><?=$item->total?></div>
                </div>
                <?php endforeach;?>
                
            </div>
            <?=Form::open('./cashier/checkout')?>
            <div class="panel-footer">
                <div class="shipping-box">
                    <h4>配送方式：</h4>
                    <?php foreach($shipping_list as $item):?>
                    <span class="radio-label">
                        <input type="radio" id="shipping<?=$item->id?>" name="shipping" value="<?=$item->id?>">
                        <label for="shipping<?=$item->id?>"><?=$item->name?></label>
                    </span>
                    <?php endforeach;?>
                </div>
                <div class="payment-box">
                    <h4>支付方式：</h4>
                    <?php foreach($payment_list as $item):?>
                    <span class="radio-label">
                        <input type="radio" id="payment<?=$item->id?>" name="payment" value="<?=$item->id?>">
                        <label for="payment<?=$item->id?>"><?=$item->name?></label>
                    </span>
                    <?php endforeach;?>
                </div>
                <div class="invoice-box">
                    <h4>发票信息：</h4>
                    <input type="checkbox" name="" id="">我要开发票
                </div>
                <div class="coupon-box">
                    <h4>使用优惠券(0张)</h4>
                    
                </div>
                
                <div class="card-box">
                    <h4>使用礼品卡</h4>
                    <input type="checkbox" name="" id="">可用余额
                </div>
                <div class="checkout-footer">
                    <?php $this->extend('./total');?>
                </div>
            </div>
            <input type="hidden" name="cart" value="<?=$this->text(request()->get('cart'))?>">
            <input type="hidden" name="type" value="<?=$this->text(request()->get('type'))?>">
            <?=Form::close()?>
        </div>
    </div>
</div>

<div id="address-dialog" class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择地址</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确定</button><button type="button" class="dialog-close">取消</button>
    </div>
</div>

<div class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">发票信息</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确定</button><button type="button" class="dialog-close">取消</button>
    </div>
</div>