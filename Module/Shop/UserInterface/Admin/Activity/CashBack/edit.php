<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '返现';
$js = <<<JS
bindCashBack();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/cash_back/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>

    <?=Form::select('scope_type', ['全部商品', '指定分类', '指定品牌', '指定商品'])?>

    <?=Form::text('scope')?>
    <?=Form::text('configure[order_amount]')->label('订单金额满')->value($configure['order_amount'])?>
    <?=Form::text('configure[money]')->label('返现金额')->value($configure['money'])?>
    <?=Form::checkbox('configure[star]')->label('是否要求5星好评')->value($configure['star'])?>


    <div class="input-group">
        <label for="start_at">起止时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" autocomplete="off" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" autocomplete="off" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>