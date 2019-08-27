<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '抽奖';
?>
<h1><?=$this->title?></h1>
<?=Form::open('./admin/activity/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::radio('活动范围', ['全部商品', '指定分类', '指定品牌', '指定商品'])?>

    <?=Form::text('优惠内容', ['满_元优惠', '满_件优惠'])?>
    <div>
        <p>订单满 <input type="text">元 </p>
        <p>订单满 <input type="text">件 </p>
        <input type="checkbox" name="">打<input type="text">折
        <input type="checkbox" name="">减<input type="text">元
        <input type="checkbox" name="">送赠品
        <input type="checkbox" name="">包邮
    </div>


    <?=Form::text('start_at', true)?>
    <?=Form::text('end_at', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>