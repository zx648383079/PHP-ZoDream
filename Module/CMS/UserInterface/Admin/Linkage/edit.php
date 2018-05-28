<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'联动菜单';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./admin/linkage/save')?>" method="post" class="form-table" role="form">
    <div class="input-group">
        <label>类型</label>
        <input value="1" name="type" type="radio" <?=$model->type == 1 ? 'checked': ''?>> 表单
        <input value="0" name="type" type="radio" <?=$model->type < 1 ? 'checked': ''?>> 实体
    </div>
    <div class="input-group">
        <label>名称</label>
        <input name="name" type="text" class="form-control" placeholder="名称" value="<?=$model->name?>">
    </div>
    <div class="input-group">
        <label>表名</label>
        <input name="code" type="text" class="form-control" placeholder="表名" value="<?=$model->table?>">
    </div>
   
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="id" value="<?=$model->id?>">
</form>