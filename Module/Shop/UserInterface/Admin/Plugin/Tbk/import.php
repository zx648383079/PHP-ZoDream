<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '导入推广位商品';
$js = <<<JS
bindDatePicker('start_time', 'end_time');
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open('./admin/plugin/tbk/import')?>
    <?=Theme::text('adzone_id', '', '推广位id', '', true)?>
    <?=Theme::text('start_time', '', '开始时间', '', true)?>
    <?=Theme::text('end_time', '', '开始时间', '', true)?>
    
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close() ?>