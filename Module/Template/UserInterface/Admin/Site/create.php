<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '新建站点';
$js = <<<JS
bindEdit();
JS;
$this->registerJs($js);
?>
<?=Form::open($model, './@admin/site/save')?>
    <?=Form::text('name', true)?>
    <?=Form::text('title', true)?>
    <?=Form::text('keywords')?>
    <?=Form::text('domain')?>
    <?=Form::file('thumb')?>
    <?=Form::textarea('description')?>
    
    <button class="btn">保存</button>
<?= Form::close('id') ?>