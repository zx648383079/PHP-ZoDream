<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '属性类型';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/attribute/save_group')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('attr_group')?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>
