<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div>
            <div class="panel">
               <div class="panel-header order-item-header">
                    <span class="time">下单时间：<?=$order->created_at?></span>
                    <span class="number">订单号：<?=$order->series_number?></span>
               </div>
                <div class="panel-body">
                    <div class="order-address">
                        <div class="address-box">
                            <div>
                                <span>收 件 人</span>
                                <span><?=$address->name?></span>
                            </div>
                            <div>
                                <span>联系方式</span>
                                <span><?=$address->tel?></span>
                            </div>
                            <div>
                                <span>收货地址</span>
                                <span><?=$address->region_name?> <?=$address->address?></span>
                            </div>
                            <div class="tip">* 确认收货得32积分</div>
                        </div>
                        <div class="order-total">
                            <div>
                            商品合计：
                            </div>
                            <div><?=$order->goods_amount?></div>
                            <div>
                            运费：
                            </div>
                            <div><?=$order->shipping_fee?></div>
                            <div class="hr"></div>
                            <div>
                            应付：
                            </div>
                            <div><?=$order->order_amount?></div>
                        </div>
                    </div>
                </div>
           </div>
           <div class="panel un-order-box">
               <div class="panel-header">
                    <span class="time">包裹 1已取消</span>
                    
                    <a href="" class="btn">再次购买</a>
               </div>
                <div class="panel-body">
                    <div class="order-goods-header has-bottom-border">
                        <div>
                        商品信息
                        </div>
                        <div>
                        单价
                        </div>
                        <div>
                        数量
                        </div>
                        <div>
                        小计
                        </div>
                        <div>
                        实付
                        </div>
                    </div>
                    <?php foreach($goods_list as $goods):?>
                    <div class="order-goods-item">
                        <div class="goods-info">
                            <div class="thumb">
                                <img src="<?=$goods->thumb?>" alt="">
                            </div>
                            <div>
                                <div class="name"><?=$goods->name?></div>
                                <div class="attr">白色</div>
                            </div>
                        </div> 
                        <div><?=$goods->price?></div>
                        <div><?=$goods->number?></div>
                        <div><?=$goods->total?></div>
                        <div><?=$goods->total?></div>
                    </div>
                    <?php endforeach;?>
                </div>
           </div>
        </div>
    </div>
</div>

