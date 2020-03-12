<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '返现';
?>
<h1><?=$this->title?></h1>
<?=Form::open('./@admin/activity/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>

    <?=Form::select('type', ['全部商品', '指定商品'])?>

    <?=Form::text('限制')?>
    <?=Form::text('订单金额满')?>
    <?=Form::text('返现金额')?>
    <?=Form::checkbox('是否要求5星好评')?>


    <?=Form::text('start_at', true)?>
    <?=Form::text('end_at', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>