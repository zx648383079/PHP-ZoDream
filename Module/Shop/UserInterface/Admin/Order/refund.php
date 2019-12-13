<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '订单退款';
?>
<h1><?=$this->title?></h1>
<?=Form::open($order, './@admin/order/save')?>
<div class="panel">
    <div class="panel-header">
        订单号：<?=$order->series_number?>
    </div>
    <div class="panel-body">
        <?=Form::select('refund_type', [$payment_list], true)?>
        <?=Form::text('money', true)->value($order->order_amount)?>
        <input type="hidden" name="operate" value="refund">

        <div class="text-center">
            <button class="btn btn-success">确认提交</button>
        </div>
    </div>
</div>
<?= Form::close('id') ?>