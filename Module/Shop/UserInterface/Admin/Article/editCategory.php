<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '分类';
?>
    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/article/save_category')?>" method="post" class="form-table" role="form">
        
        <div class="input-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入分类名" value="<?=$model->name?>" required>
        </div>
        <div class="input-group">
            <label>上级分类</label>
            <select name="parent_id">
              <option value="0">-- 无上级分类 --</option>
                <?php foreach($cat_list as $item):?>
                <?php if($item['id'] != $model->id):?>
                <option value="<?=$item['id']?>" <?= $item['id'] == $model->parent_id ? 'selected' : '' ?>><?=$item['name']?></option>
                <?php endif;?>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label for="cover">图片</label>
            <div class="file-input">
                <input type="text" id="cover" name="thumb" placeholder="请输入图片" value="<?=$model->thumb?>">
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