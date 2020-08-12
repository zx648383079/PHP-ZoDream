<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的订单';
$this->extend('layouts/main');
?>

<div class="container main-box">
    <div class="tab-bar">
        <a href="<?=$this->url('./runner')?>">返回接单大厅</a>
        <a href="<?=$this->url('./order')?>" class="<?=$status < 1 ? 'active' : ''?>">全部</a>
        <?php foreach($status_list as $key => $item):?>
        <a href="<?=$this->url('./order', ['status' => $key])?>"  class="<?=$status == $key ? 'active' : ''?>"><?=$item?></a>
        <?php endforeach;?>
    </div>

    <?php foreach($order_list as $item):?>
    <div class="order-item">
        <div class="order-header">
            <span><?= $item->id?></span>
            <span class="status"><?= $item->status_label?></span>
        </div>
            <div class="order-amount">
            <span>【<?= $item->service->name?>】</span>
            <span class="amount">x <?= $item->amount?></span>
            <span class="price">
                服务费：￥<?= $item->order_amount?>
            </span>
        </div>
        <?php if($item->remark):?>
        <div class="order-remark">
            <?php foreach($item->remark as $line):?>
            <p class="line-item" >
                <span><?=$line['label']?></span>
                <span class="val"><?=$line['value']?></span>
            </p>
            <?php endforeach;?>
        </div>
        <?php endif;?>
        <div class="order-footer">
            <div class="order-actions">
                <?php if($item->status == 40):?>
                   <a data-type="del" href="<?=$this->url('./runner/taken', ['id' => $item->id])?>" data-tip="确定已完成此订单?">结单</a>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <div>
        <?=$order_list->getLink()?>
    </div>
</div>

