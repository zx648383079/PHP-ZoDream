<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '广告';
$js = <<<JS
bindEditAd();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/ad/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('position_id', [$position_list])?>
    <?=Form::select('type', $model->type_list)?>

    <div class="type-group">
        <?=Form::textarea('content')?>
        <?=Form::file('content')->name('content_url')?>
    </div>

    <?=Form::text('url')?>
    <?=Form::text('start_at', true)?>
    <?=Form::text('end_at', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>