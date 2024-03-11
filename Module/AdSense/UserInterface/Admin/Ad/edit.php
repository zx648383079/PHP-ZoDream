<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '广告';
$js = <<<JS
var groups = $(".type-group .input-group");
$("select[name=type]").on('change', function() {
    groups.eq(parseInt($(this).val()) % 2).show().siblings().hide();
}).trigger('change');
JS;
$this->registerJs($js, View::JQUERY_READY);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/ad/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('position_id', [$position_list])?>
    <?=Form::select('type', ['文本', '图片', '代码', '视频'])?>

    <div class="type-group">
        <?=Form::textarea('content')?>
        <?=Form::file('content')->name('content_url')?>
    </div>

    <?=Form::text('url')?>
    <?=Form::datetime('start_at')?>
    <?=Form::datetime('end_at')?>
    <?=Form::switch('status')?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>