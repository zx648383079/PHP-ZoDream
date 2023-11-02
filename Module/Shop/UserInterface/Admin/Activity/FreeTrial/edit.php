<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '免费试用';
$js = <<<JS
bindFreeTrial();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/free_trial/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::text('scope', true)->label('商品')?>
    <?=Form::text('configure[amount]', true)->label('试用数量')?>
    <div class="input-group">
        <label for="start_at">起止时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" autocomplete="off" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" autocomplete="off" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>