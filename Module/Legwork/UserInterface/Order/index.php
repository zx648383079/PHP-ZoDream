<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的订单';
$this->extend('layouts/main');
$js = <<<JS
bindOrder();
JS;
$this->registerJs($js);
?>

<div class="container main-box">
    <div class="tab-bar">
        <a href="<?=$this->url('./')?>">返回</a>
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
                <?php if($item->status == 10):?>
                   <a href="<?=$this->url('./order/pay', ['id' => $item->id])?>">支付</a>
                <?php endif;?>
                <?php if($item->status == 60):?>
                   <a data-action="comment" href="<?=$this->url('./order/comment', ['id' => $item->id])?>">评价</a>
                <?php endif;?>
                <?php if($item->status == 10):?>
                   <a data-type="del" href="<?=$this->url('./order/cancel', ['id' => $item->id])?>" data-tip="确定取消此订单?">取消</a>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <div>
        <?=$order_list->getLink()?>
    </div>
</div>

