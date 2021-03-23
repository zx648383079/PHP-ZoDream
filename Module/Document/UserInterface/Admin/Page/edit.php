<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = $model->id ?  '编辑文档:'.$model->name : '新建文档';
$js = <<<JS
$('[name=parent_id]').change(function () { 
    $(".extent-box").toggle($(this).val() > 0);
});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?= Form::open($model, './@admin/page/save') ?>
    <?= Form::text('name', true) ?>
    <?= Form::select('parent_id', [$tree_list, [0 => '-- 顶级 --']])?>

    <div class="extent-box" <?=$model->type > 0 ? ' style="display:none"' : ''?>>
        <textarea id="editor-container" name="content"><?=$model->content?></textarea>
    </div>


    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="project_id" value="<?=$model->project_id?>">
    <input type="hidden" name="version_id" value="<?=$model->version_id?>">
<?= Form::close('id') ?>
