<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '优惠券';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/activity/coupon/save')?>
    <?=Form::text('name', true)?>
    <?=Form::file('thumb')?>
    <?=Form::select('type', ['优惠', '折扣'])?>
    <?=Form::select('rule', ['全场通用', '指定商品', '指定分类', '指定品牌', '指定店铺'])?>
    <?=Form::text('min_money')?>
    <?=Form::text('money')?>
    <?=Form::select('send_type', ['前台领取', '购买商品', '订单金额', '签到', '按用户'])?>
    <div>
        <?=Form::text('total_amount')?>
    </div>
    <?=Form::text('every_amount')?>
    <div class="input-group">
        <label for="start_at">有效期</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>
