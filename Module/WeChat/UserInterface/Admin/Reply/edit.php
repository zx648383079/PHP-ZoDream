<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑消息';
$js = <<<JS
$('#event').change(function() {
    $(".message-box").toggle($(this).val() == 'message');
    $(".click-box").toggle($(this).val() == 'CLICK');
});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>无任何关键词时自动回复</li>
    </ul>
    <span class="toggle"></span>
</div>

<?=Form::open($model, './admin/reply/save')?>
    <?=Form::select('event', $event_list)?>
    <div class="message-box" <?= $model->event != 'message' ? 'style="display:none"': ''?>>
        <?=Form::text('keywords')?>
        <?=Form::radio('match', ['部分匹配', '完全匹配'])?>
    </div>
    <div class="click-box" <?= $model->event != 'click' ? 'style="display:none"': ''?>>
        <?=Form::text('event_name')->value($model->keywords)?>
    </div>
    <?php $this->extend('../layouts/editor'); ?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>