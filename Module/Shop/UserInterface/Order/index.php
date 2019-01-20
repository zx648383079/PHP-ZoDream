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
           <div class="order-search">
               <div>
                    <div class="order-tab">
                            <a href="" class="active">全部订单</a>
                            <a href="">待付款  1</a>
                            <a href="">待发货 </a>
                            <a href="">已发货</a>
                            <a href="">待评价 </a>
                    </div>
               </div>
               <div class="search-box">
                   <input type="text">
                   <button>搜索</button>
               </div>
           </div>
           <?php foreach($order_list as $order):?>
           <div class="panel">
               <div class="panel-header order-item-header">
                    <span class="time">下单时间：<?=$order->created_at?></span>
                    <span class="number">订单号：<?=$order->id?></span>
                    <a href="">
                        <i class="fa fa-trash"></i>
                    </a>
               </div>
                <div class="panel-body">
                    <?php foreach($order->goods as $goods):?>
                    <div class="order-item">
                        <div class="goods-img">
                            <img src="<?=$goods->thumb?>" alt="">
                        </div>
                        <div class="name"><?=$goods->name?></div>
                        <div class="status">
                            <span><?=$order->status_label?></span>
                            <a href="">再次购买</a>
                        </div>
                        <div class="price">
                            <?=$goods->price?>
                            <p>（含运费：¥0.00元）</p>
                        </div>
                        <div class="actions">
                            <a href="<?=$this->url('./cashier/pay', ['id' => $order->id])?>" class="btn">付款</a>
                            <a href="<?=$this->url('./order/detail', ['id' => 1])?>">查看详情</a>
                            <a href="">取消订单</a>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
           </div>
           <?php endforeach;?>
        </div>
    </div>
</div>

