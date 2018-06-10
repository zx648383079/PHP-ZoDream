<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/category/save')?>" method="post" class="form-table" role="form">
    <div class="zd-tab">
            <div class="zd-tab-head">
                <div class="zd-tab-item active">
                    基本
                </div>
                <div class="zd-tab-item">
                    详情
                </div>
                <div class="zd-tab-item">
                    模板
                </div>
            </div>
            <div class="zd-tab-body">
                <div class="zd-tab-item active">
                    <div class="input-group">
                        <label>名称</label>
                        <input name="title" type="text" class="form-control" required  placeholder="输入名称" value="<?=$model->title?>">
                    </div>
                    <div class="input-group">
                        <label>目录名</label>
                        <input name="name" type="text" class="form-control" required placeholder="输入目录名" value="<?=$model->name?>">
                    </div>
                    <div class="input-group">
                        <label>类型</label>
                        <input value="0" name="type" type="radio" <?=$model->type < 1 ? 'checked': ''?>> 内容
                        <input value="1" name="type" type="radio" <?=$model->type == 1 ? 'checked': ''?>> 单页
                        <input value="2" name="type" type="radio" <?=$model->type == 2 ? 'checked': ''?>> 外链
                    </div>
                    <div class="input-group">
                        <label>模型</label>
                        <select name="model_id">
                        <option value="0">-- 无 --</option>
                            <?php foreach($model_list as $item):?>
                            <option value="<?=$item['id']?>" <?= $item['id'] == $model->model_id ? 'selected' : '' ?>><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>上级</label>
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
                    <div class="input-group">
                        <label>排序</label>
                        <input name="position" type="text" class="form-control" placeholder="排序" value="<?=$model->position ?: 99?>">
                    </div>
                </div>
                <div class="zd-tab-item">
                    <script id="container" style="height: 400px" name="content" type="text/plain" required>
                        <?=$model->content?>
                    </script>
                </div>
                <div class="zd-tab-item">
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
                </div>
            </div>
        </div>

        

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>