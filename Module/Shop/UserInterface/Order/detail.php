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
                    <span class="time">下单时间：2018-11-20 22:13:25</span>
                    <span class="number">订单号：69365568</span>
               </div>
                <div class="panel-body">
                    <div class="order-address">
                        <div class="address-box">
                            <div>
                                <span>收 件 人</span>
                                <span>111</span>
                            </div>
                            <div>
                                <span>联系方式</span>
                                <span>111</span>
                            </div>
                            <div>
                                <span>收货地址</span>
                                <span>111</span>
                            </div>
                            <div class="tip">* 确认收货得32积分</div>
                        </div>
                        <div class="order-total">
                            <div>
                            商品合计：
                            </div>
                            <div>111</div>
                            <div>
                            运费：
                            </div>
                            <div>111</div>
                            <div class="hr"></div>
                            <div>
                            应付：
                            </div>
                            <div>111</div>
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
                    <div class="order-item has-bottom-border">
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
                    <div class="order-item">
                        <div>
                            <img src="http://yanxuan.nosdn.127.net/9014a75315685c0ecccece8583fdba68.png?imageView&thumbnail=100x100&quality=95" alt="">
                        </div>
                        <div class="name">男式都市运动外套</div>
                        <div class="status">
                            <span>未付款</span>
                            <a href="">再次购买</a>
                        </div>
                        <div class="price">
                            ¥329.00
                            <p>（含运费：¥0.00元）</p>
                        </div>
                        <div class="actions">
                            <a href="" class="btn">付款</a>
                            <a href="<?=$this->url('./order/detail', ['id' => 1])?>">查看详情</a>
                            <a href="">取消订单</a>
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>

