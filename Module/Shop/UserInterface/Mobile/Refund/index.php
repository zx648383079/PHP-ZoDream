<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '退换货订单管理';

$this->extend('../layouts/header');
?>
<div class="has-header">
    <div class="tab-bar order-header">
        <a href="" class="active">售后申请</a>
        <a href="">处理中</a>
        <a href="">记录</a>
    </div>

    <div class="refund-box">
        <?php foreach($goods_list as $goods):?>
        <div class="goods-item">
            <div class="goods-img">
                <img src="<?=$goods->thumb?>" alt="">
            </div>
            <div class="goods-info">
                <h4><?=$goods->name?></h4>
                <span class="amount"> x <?=$goods->amount?></span>
                <a class="action" href="<?=$this->url('./mobile/refund/create', ['goods' => $goods->id])?>">申请售后</a>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>