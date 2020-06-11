<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '接口配置';
$js = <<<JS
$('#platform_id').change(function() {
    var id = $(this).val();
    if (id < 1) {
        return;
    }
    $.get('{$url}edit_option', {
        platform_id: id
    }, function(html) {
        $('.edit-view').html(html);
    });
}).trigger('change');
JS;
$this->registerJs($js, View::JQUERY_READY);
?>
<?=Form::open($url. 'save_option')?>
    <?=Form::select('platform_id', [$items])->label('应用')?>

    <div class="edit-view">
    
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>