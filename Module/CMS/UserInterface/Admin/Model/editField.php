<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'字段';
?>

<h1><?=$this->title?></h1>
<form data-type="ajax" action="<?=$this->url('./admin/model/save_field')?>" method="post" class="form-table" role="form">
    <div class="input-group">
        <label>名称</label>
        <input name="name" type="text" class="form-control" placeholder="名称" value="<?=$model->name?>">
    </div>
    <div class="input-group">
        <label>字段名</label>
        <input name="field" type="text" class="form-control" placeholder="字段名" value="<?=$model->field?>">
    </div>
    <div class="input-group">
        <label>类型</label>
        <select name="type" required>
            <?php foreach($model->type_list as $key => $item):?>
            <option value="<?=$key?>" <?=$item == $model->type ? 'selected' : ''?>><?=$item?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="input-group">
        <label>是否必填</label>
        <input value="1" name="is_required" type="radio" <?=$model->is_required == 1 ? 'checked': ''?>> 必填
        <input value="0" name="is_required" type="radio" <?=$model->is_required < 1 ? 'checked': ''?>> 非必填
    </div>
    <div class="input-group">
        <label>长度</label>
        <input name="length" type="text" class="form-control" placeholder="长度" value="<?=$model->length?>">
    </div>
    <div class="input-group">
        <label>匹配规则</label>
        <input name="match" type="text" class="form-control" placeholder="匹配规则" value="<?=$model->match?>">
    </div>
    <div class="input-group">
        <label>提示信息</label>
        <input name="tip_message" type="text" class="form-control" placeholder="提示信息" value="<?=$model->tip_message?>">
    </div>
    <div class="input-group">
        <label>错误提示</label>
        <input name="error_message" type="text" class="form-control" placeholder="错误提示" value="<?=$model->error_message?>">
    </div>
    <div class="input-group">
        <label>排序</label>
        <input name="position" type="text" class="form-control" placeholder="排序" value="<?=$model->position?>">
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="id" value="<?=$model->id?>">
    <input type="hidden" name="model_id" value="<?=$model->model_id?>">
</form>