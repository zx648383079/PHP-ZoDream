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
           <div class="panel">
               <div class="panel-header order-item-header">
                    <span class="time">下单时间：2018-11-20 22:13:25</span>
                    <span class="number">订单号：69365568</span>
                    <a href="">
                        <i class="fa fa-trash"></i>
                    </a>
               </div>
                <div class="panel-body">
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

