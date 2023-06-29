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
$('#container').editor();
JS;
$this->registerJsFile([
    '@jquery.editor.min.js',
])
->registerCssFile([
    '@editor.css',
])
->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?= Form::open($model, './@admin/page/save') ?>
    <?= Form::text('name', true) ?>
    <?= Form::select('parent_id', [$tree_list, [0 => '-- 顶级 --']])?>

    <div class="extent-box" <?=$model->parent_id < 1 ? ' style="display:none"' : ''?>>
        <script id="container" style="height: 600px" name="content" type="text/plain" required>
            <?=$model->content?>
        </script>
    </div>


    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="project_id" value="<?=$model->project_id?>">
<?= Form::close('id') ?>
