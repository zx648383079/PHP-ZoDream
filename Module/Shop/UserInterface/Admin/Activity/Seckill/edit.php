<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '秒杀活动';
$js = <<<JS
    var start_at = $('[name=start_at]').datetimer();
    var end_at = $('[name=end_at]').datetimer({
        min: start_at
    });
JS;
$this->registerJs($js, View::JQUERY_READY);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/seckill/save')?>
    <?=Form::text('name')?>
    <div class="input-group">
        <label for="start_at">起止时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    
    <?=Form::radio('状态', ['开启', '关闭'])?>
    
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>