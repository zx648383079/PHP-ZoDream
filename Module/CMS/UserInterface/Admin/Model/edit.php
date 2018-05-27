<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'模块';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./admin/model/save')?>" method="post" class="form-table" role="form">
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
        <input name="table" type="text" class="form-control" placeholder="表名" value="<?=$model->table?>">
    </div>
    <div class="input-group">
        <label>分类模板</label>
        <input name="category_template" type="text" class="form-control" placeholder="分类模板" value="<?=$model->category_template?>">
    </div>
    <div class="input-group">
        <label>列表模板</label>
        <input name="list_template" type="text" class="form-control" placeholder="列表模板" value="<?=$model->list_template?>">
    </div>
    <div class="input-group">
        <label>详情模板</label>
        <input name="show_template" type="text" class="form-control" placeholder="详情模板" value="<?=$model->show_template?>">
    </div>
    <div class="input-group">
        <label>排序</label>
        <input name="position" type="text" class="form-control" placeholder="排序" value="<?=$model->position?>">
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="id" value="<?=$model->id?>">
</form>