<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'住宅';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/house/save')?>
    <?=Form::text('name', true)?>
    <?=Form::text('amount', true)?>
    <?=Form::text('price', true)?>
    <?=Form::text('resume_speed', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>