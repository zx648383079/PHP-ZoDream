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
                <div>
                    <h4>使用优惠券(0张)</h4>
                    
                </div>
                <div  class="amount-box">
                    <div>商品合计:<?=$order->goods_amount?></div>
                    <div>运费:<?=$order->shipping_fee?></div>
                </div>
                <div>
                    <h4>使用礼品卡</h4>
                    <input type="checkbox" name="" id="">可用余额
                </div>
                <div class="checkout-footer">
                   <div>应付总额:<?=$order->order_amount?></div>
                    <a href="<?=$this->url('./cashier/checkout')?>" class="btn">去付款</a>
                    <?php if($address):?>
                    <div><?=$address->name?> <?=$address->tel?></div>
                    <div><?=$address->region->full_name?> <?=$address->address?></div>
                    <?php endif;?>
                    <input type="hidden" name="address" value="<?=$address ? $address->id : ''?>">
                    <input type="hidden" name="cart" value="<?=$this->text(app('request')->get('cart'))?>">
                    <input type="hidden" name="type" value="<?=$this->text(app('request')->get('type'))?>">
                </div>
            </div>
            <?=Form::close()?>
        </div>
    </div>
</div>

<div class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择地址</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="address-item active">
            <div>收货人: 111</div>
            <div>联系方式：186****1369</div>
            <div>收货地址：上海市上海市长宁区江苏路街道2369号405</div>
            <span>默认地址</span>
        </div>
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