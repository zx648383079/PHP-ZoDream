<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '砍价';
$js = <<<JS
bindBargain();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/bargain/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::text('scope', true)->label('商品')?>
    <div class="input-group">
        <label for="start_at">单次砍价范围</label>
        <div class="">
            <input type="text" id="min" class="form-control " name="configure[min]" placeholder="请输入最低砍价">
            ~
            <input type="text" id="max" class="form-control " name="configure[max]" placeholder="请输入最高砍价">
        </div>
    </div>

    <?=Form::text('configure[times]')->label('砍价次数')?>
    <?=Form::text('configure[amount]')->label('商品件数')?>

    <?=Form::text('configure[shipping_fee]')->label('运费')?>

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