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
           <div>
               <div>
                <a href="" class="active">全部订单</a>
                    <a href="">待付款  1</a>
                    <a href="">待发货 </a>
                    <a href="">已发货</a>
                    <a href="">待评价 </a>
               </div>
               <div>
                   <input type="text">
                   <button>搜索</button>
               </div>
           </div>
           <div class="panel">
               <div class="panel-header">
                    <div>下单时间：2018-11-20 22:13:25</div>
                    <div>订单号：69365568</div>
               </div>
                <div class="panel-body">
                <div class="order-item">
                        <div>
                            <img src="" alt="">
                        </div>
                        <div>包裹1</div>
                        <div>未付款</div>
                        <div>
                            ¥329.00
                            <p>（含运费：¥0.00元）</p>
                        </div>
                        <div>
                            <a href="" class="btn">付款</a>
                            <a href="">查看详情</a>
                            <a href="">取消订单</a>
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>

