<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '分类';
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/term/save')?>" method="post" class="form-table" role="form">
        
        <div class="input-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入形态名称" value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>关键词</label>
            <textarea name="keywords" class="form-control" placeholder="关键词"><?=$model->keywords?></textarea>
        </div>
        <div class="input-group">
            <label>简介</label>
            <textarea name="description" class="form-control" placeholder="简介"><?=$model->description?></textarea>
        </div>
        <div class="input-group">
            <label for="thumb">图片</label>
            <div class="file-input">
                <input type="text" id="thumb" name="thumb" placeholder="请输入图片" value="<?=$model->thumb?>" size="70">
                <button type="button" data-type="upload">上传</button>
                <button type="button" data-type="preview">预览</button>
            </div>
        </div>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>