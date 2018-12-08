<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '申请开票';
$this->extend('../layouts/header')
?>
<div class="has-header has-double-footer">
    <?php foreach(range(1, 5) as $item):?>
    <div class="order-mini-item">
        <i class="fa check-box"></i>
        <div class="info">
            <div class="name"><?=$item?></div>
            <p><?=date('Y-m-d H:i:s')?></p>
        </div>
        <div class="amount">
            ￥200
        </div>
    </div>
    <?php endforeach;?>
</div>
<div class="invoice-footer">
    <div>
        <i class="fa check-box"></i>
        全选
    </div>
    <div>
        已选择0笔订单
    </div>
    <div>
        <p>可开票金额￥0</p>
        <p>已选金额￥0</p>
    </div>
    <div>
        <a href="">立即开票</a>
    </div>
</div>