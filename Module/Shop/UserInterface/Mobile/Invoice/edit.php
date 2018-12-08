<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑抬头';
$js = <<<JS
$('select').select();
JS;
$this->extend('../layouts/header')
    ->registerCssFile('@dialog-select.css')
    ->registerJsFile('@jquery.selectbox.min.js')
    ->registerJs($js);
?>

<div class="has-header">
    <?=Form::open($model, './mobile/invoice/save')?>
        <?=Form::text('发票抬头', true)?>
        <?=Form::radio('发票抬头类型', ['个人', '企业'])?>
        <?=Form::select('发票类型', ['增值税普通发票', '增值税专用发票'])?>
        <?=Form::text('税务登记号', true)?>
        <?=Form::text('注册场所地址', true)?>
        <?=Form::text('注册场所电话', true)?>
        <?=Form::text('开户银行', true)?>
        <?=Form::text('基本开户账号', true)?>
    <?=Form::close('id')?>
</div>

<div class="fixed-footer">
    <button class="btn" type="button">保存</button> 
</div>
