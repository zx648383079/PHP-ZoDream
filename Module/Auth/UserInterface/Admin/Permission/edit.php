<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '权限';
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/permission/save')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>权限名</label>
            <div>
                <input name="name" type="text" class="form-control"  placeholder="输入角色名" required value="<?=$model->name?>">
            </div>
        </div>
        <div class="input-group">
            <label>别名</label>
            <div>
                <input name="display_name" type="text" class="form-control"  placeholder="输入别名" value="<?=$model->display_name?>">
            </div>
        </div>
        <div class="input-group">
            <label>简介</label>
            <textarea name="description" class="form-control" placeholder="简介"><?=$model->description?></textarea>
        </div>
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>