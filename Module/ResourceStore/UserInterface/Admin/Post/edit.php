<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = ($model->id > 0 ? '编辑' : '新增'). '文章';
$js = <<<JS
bindEdit();
JS;

$this->registerJs($js);
?>

<div class="page-title">
    <h1><?=$this->title?></h1>
</div>
<?=Form::open($model, './@admin/post/save', ['enctype' => 'multipart/form-data',])?>
    <div class="tab-box">
        <div class="tab-header">
            <div class="tab-item active">
                基本
            </div><div class="tab-item">
                详情
            </div>
        </div>
        <div class="tab-body">
            <div class="tab-item active">
                <?=Form::text('title', true)?>
                <div class="input-group">
                    <label>分类</label>
                    <select name="cat_id" required>
                        <?php foreach($cat_list as $item):?>
                        <option value="<?=$item['id']?>" <?=$model->cat_id == $item['id'] ? 'selected': '' ?>>
                            <?php if($item['level'] > 0):?>
                                ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                            <?php endif;?>
                            <?=$item['name']?>
                        </option>
                        <?php endforeach;?>
                    </select>
                </div>
                
                <?=Form::file('thumb')?>
                <div class="input-group">
                    <label for="file">文件</label>
                    <div class="file-input">
                        <input type="text" id="file" class="form-control " name="file" value="<?=$model->file?>">
                        <button type="button" data-type="upload_file">上传</button>
                    </div>
                </div>
                
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
                <div class="input-group">
                    <label>标签</label>
                    <div>
                        <select name="tag[]" id="tag-box" multiple style="width: 100%">
                            <?php foreach($tags as $item):?>
                            <option value="<?=$item['id']?>" selected><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="tab-item">
                <textarea id="editor-container" name="content" required><?=$model->content?></textarea>
            </div>
        </div>
    </div>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>