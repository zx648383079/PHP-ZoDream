<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'字段';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./admin/linkage/save_data')?>" method="post" class="form-table" role="form">
    <div class="input-group">
        <label>名称</label>
        <input name="name" type="text" class="form-control" placeholder="名称" value="<?=$model->name?>">
    </div>
    <div class="input-group">
        <label>排序</label>
        <input name="position" type="text" class="form-control" placeholder="长度" value="<?=$model->position?>">
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="id" value="<?=$model->id?>">
    <input type="hidden" name="linkage_id" value="<?=$model->linkage_id?>">
    <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
</form>