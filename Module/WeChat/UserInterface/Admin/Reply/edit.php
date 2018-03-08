<?php
use Zodream\Template\View;
/** @var $this View */
$this->extend('../layouts/header');
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

<div>
    <form class="form-inline" data-type="ajax" action="<?=$this->url('./admin/reply/save')?>" method="post">
        <div class="input-group">
            <label for="event">事件</label>
            <select name="event" id="event" required>
                <?php foreach($event_list as $key => $item):?>
                <option value="<?=$key?>" <?=$key == $model->event ? 'selected' : ''?>><?=$item?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="message-box" <?= $model->event != 'message' ? 'style="display:none"': ''?>>
            <div class="input-group">
                <label for="keywords">关键字</label>
                <input type="text" id="keywords" name="keywords" placeholder="关键字" value="<?=$model->keywords?>" size="100">
            </div>
            <div class="input-group">
                <label>匹配方式</label>
                <label for="match_0">
                    <input type="radio" id="match_0" name="match" value="0" <?=0 == $model->event ? 'checked' : ''?>>
                    部分匹配
                </label>
                <label for="match_1">
                    <input type="radio" id="match_1" name="match" value="1" <?=1 == $model->event ? 'checked' : ''?>>
                    完全匹配
                </label>
            </div>
        </div>
        <div class="click-box" <?= $model->event != 'click' ? 'style="display:none"': ''?>>
            <div class="input-group">
                <label for="event_name">事件名</label>
                <input type="text" id="event_name" name="event_name" placeholder="事件名" value="<?=$model->keywords?>" size="100">
            </div>
        </div>
        <?php $this->extend('layouts/editor'); ?>
        <button class="btn btn-primary">保存</button>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>

<?php $this->extend('../layouts/footer');?>