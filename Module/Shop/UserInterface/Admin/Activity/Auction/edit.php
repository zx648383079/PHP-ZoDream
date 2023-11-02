<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '拍卖';
$js = <<<JS
bindAuction();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/auction/save')?>
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
    
    <?=Form::radio('configure[mode]', ['普通拍卖', '荷兰式拍卖'])->label('拍卖模式')->tip('普通拍卖为加价拍卖，荷兰式拍卖为先降价直到有人拍，如果两个同时拍则开始加价') ?>
    <?=Form::text('configure[begin_price]')->label('起拍价')?>
    <?=Form::text('configure[fixed_price]')->label('一口价')?>
    <?=Form::text('configure[step_price]')->label('加价幅度')?>
    <?=Form::text('configure[deposit]')->label('保证金')?>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>
