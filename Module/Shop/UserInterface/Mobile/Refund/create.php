<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '申请退换货';
$this->extend('../layouts/header');
?>

<div class="has-header">
    <?=Form::open(null, './mobile/refund/save')?>
        <?=Form::radio('退款类型', ['仅退款', '退货退款'])?>
        <?=Form::text('退款金额', true)?>
        <?=Form::text('退款说明', true)?>
        <?=Form::text('上传图片', true)?>
    <?=Form::close('id')?>
</div>

<div class="fixed-footer">
    <button class="btn" type="button">保存</button> 
</div>
