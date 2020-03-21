<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '团购';
$js = <<<JS
bindGroupBuy();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/group_buy/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::text('scope', true)->label('商品')?>
    <div class="input-group">
        <label for="start_at">起止时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" autocomplete="off" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" autocomplete="off" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <?=Form::text('configure[deposit]')->label('保证金')?>
    <?=Form::text('configure[amount]')->label('限购数量')?>
    <?=Form::text('configure[send_point]')->label('赠送积分')?>
    <div class="input-group">
        <label for="money">价格阶梯</label>
        <div class="">
            <p class="step-item">数量达到<input type="text" size="10" name="configure[step][amount][]">
            享受价格<input type="text" size="10" name="configure[step][price][]"></p>
            <a href="javascript:;" class="btn" data-action="add">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
    
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>
