<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'模块';
$js = <<<JS
bindEditModel();
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/model/save')?>
    <?=Form::radio('type', [1 => '表单', 0 => '实体'])?>
    <?=Form::text('name', true)?>
    <?=Form::text('table', true)->readonly($model->id > 0)?>
    <div class="content-box">
        <?=Form::select('child_model', [$model_list, ['无分集']])?>
        <?=Form::text('category_template', true)?>
        <?=Form::text('list_template', true)?>
        <?=Form::text('show_template', true)?>
    </div>
    <div class="form-box">
        <?=Form::checkbox('setting[is_show]')->value($model->setting('is_show'))->label('显示')->tip('允许显示在前台')?>
        <?=Form::checkbox('setting[is_only]')->value($model->setting('is_only'))->label('唯一')->tip('是否保持用户唯一')?>
    </div>
    <?=Form::text('position')?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?=Form::close('id')?>