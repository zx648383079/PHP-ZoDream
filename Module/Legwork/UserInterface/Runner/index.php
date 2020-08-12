<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的订单';
$this->extend('layouts/main');
?>

<div class="container main-box">

    <div class="tab-bar">
        <a href="<?=$this->url('./runner/order')?>">已接的单</a>
    </div>

    <?php foreach($order_list as $item):?>
    <div class="order-item">
        <div class="order-header">
            <span><?= $item->id?></span>
            <span class="status"><?= $item->status_label?></span>
        </div>
            <div class="order-amount">
            <span>【<?= $item->service->name?>】</span>
            <span class="amount">x <?= $item->amount ?></span>
            <span class="price">
                服务费：￥<?= $item->order_amount?>
            </span>
        </div>
        <div class="order-footer">
            <div class="order-actions">
                <a data-type="ajax" href="<?=$this->url('./runner/taking', ['id' => $item->id])?>">接单</a>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <div>
        <?=$order_list->getLink()?>
    </div>
</div>

