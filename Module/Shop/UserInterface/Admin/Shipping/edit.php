<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '配送方式';
?>
    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/shipping/save')?>" method="post" class="form-table" role="form">
        
        <div class="input-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入名称" value="<?=$model->name?>" required>
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
            <label>配送模型</label>
            <select name="parent_id">
                <?php foreach($shipping_list as $key => $item):?>
                <option value="<?=$key?>" <?= $key == $model->code ? 'selected' : '' ?>><?=$item?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label>说明</label>
            <textarea name="description" class="form-control" placeholder="简介"><?=$model->description?></textarea>
        </div>
        <div class="input-group">
            <label>收费</label>
            <input name="fee" type="text" class="form-control"  placeholder="输入收费" value="0" required>
        </div>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>