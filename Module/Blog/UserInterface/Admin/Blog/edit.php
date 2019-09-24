<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$lang_list = ['Html', 'Css', 'Sass', 'Less', 'TypeScript', 'JavaScript', 'PHP', 'Go', 'C#', 'ASP.NET', '.NET Core', 'Python', 'C', 'C++', 'Java', 'Kotlin', 'Swift', 'Objective-C', 'Dart', 'Flutter'];
$this->title = ($model->id > 0 ? '编辑' : '新增'). '文章';
$configs = app('request')->isMobile() ?
    '{toolbars: [[\'fullscreen\', \'source\', \'undo\', \'redo\', \'bold\', \'italic\', \'underline\', \'customstyle\', \'link\',\'simpleupload\', \'insertvideo\']],}' : '{}';
$url = $this->url('./admin', false);
$tags = json_encode($tags);
$js = <<<JS
bindEdit({$configs}, '{$url}', {$tags});
JS;

$this->registerJs($js);
?>

<div class="page-title">
    <h1><?=$this->title?></h1>
    <?php if($model->id > 0 || $model->parent_id > 0):?>
    <div class="language-toggle">
    切换到
    <?php if($model->parent_id < 1):?>
     <a href="<?=$this->url('./admin/blog/edit', ['id' => $model->id, 'language' => 'en'])?>">EN</a>
     <?php else:?>
     <a href="<?=$this->url('./admin/blog/edit', ['id' => $model->parent_id])?>">中</a>
    <?php endif;?>
    </div>
    <?php endif;?>
</div>
<?=Form::open($model, './admin/blog/save')?>
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
                <?php if($model->parent_id < 1):?>
                <?=Form::select('term_id', [$term_list], true)?>
                <?=Form::select('programming_language', array_merge(['' => '请选择'], array_combine($lang_list, $lang_list)))?>
                <?=Form::select('type', ['原创', '转载'])?>
                <?=Form::text('source_url')?>
                <?php endif;?>
                <?=Form::select('edit_type', ['Ueditor', 'MarkDown'])?>
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
                <?php if($model->parent_id < 1):?>
                <?=Form::checkbox('comment_status')?>
                <div class="input-group">
                    <label>标签</label>
                    <div>
                        <select name="tag[]" id="tag-box" multiple style="width: 100%">
                        </select>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <div class="zd-tab-item">
                <textarea id="editor-container" style="height: 400px;" name="content" required><?=$model->content?></textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="language" value="<?=$model->language ?: 'zh'?>">
    <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
<?= Form::close('id') ?>