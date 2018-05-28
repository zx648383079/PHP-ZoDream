<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '小说';
?>
    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/book/save')?>" method="post" class="form-table" role="form">
        
        <div class="input-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入小说名称" value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>分类</label>
            <select name="cat_id" required>
                <option value="">请选择</option>
                <?php foreach($cat_list as $item):?>
                <option value="<?=$item->id?>" <?=$item->id == $model->cat_id ? 'selected' : ''?>><?=$item->real_name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label>作者</label>
            <select name="author_id" required>
                <option value="">请选择</option>
                <?php foreach($author_list as $item):?>
                <option value="<?=$item->id?>" <?=$item->id == $model->author_id ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label for="cover">封面</label>
            <div class="file-input">
                <input type="text" id="cover" name="cover" placeholder="请输入小说封面" value="<?=$model->cover?>" size="70">
                <button type="button" data-type="upload" data-grid="cover">上传</button>
                <button type="button" data-type="preview">预览</button>
            </div>
        </div>
        <div class="input-group">
            <label>关键词</label>
            <textarea name="keywords" class="form-control" placeholder="关键词"><?=$model->keywords?></textarea>
        </div>
        <div class="input-group">
            <label>简介</label>
            <textarea name="description" class="form-control" placeholder="简介"><?=$model->description?></textarea>
        </div>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>