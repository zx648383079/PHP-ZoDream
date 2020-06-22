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
<?=Form::open($model, './@admin/post/save')?>
    <div class="zd-tab">
        <div class="zd-tab-head">
            <div class="zd-tab-item active">
                基本
            </div><div class="zd-tab-item">
                详情
            </div>
        </div>
        <div class="zd-tab-body">
            <div class="zd-tab-item active">
                <?=Form::text('title', true)?>
                <?=Form::select('cat_id', [$cat_list], true)?>
                
                <?=Form::file('thumb')?>
                <?=Form::file('file')?>
                
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
            <div class="zd-tab-item">
                <textarea id="editor-container" style="height: 400px;" name="content" required><?=$model->content?></textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>