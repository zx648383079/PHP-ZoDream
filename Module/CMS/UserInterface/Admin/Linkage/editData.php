<?php
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'联动项';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/linkage/save_data')?>
<form data-type="ajax" action="<?=$this->url('./admin/linkage/save_data')?>" method="post" class="form-table" role="form">
    <?=Form::text('name', true)?>
    <?=Form::text('position', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="linkage_id" value="<?=$model->linkage_id?>">
    <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
<?=Form::close('id')?>