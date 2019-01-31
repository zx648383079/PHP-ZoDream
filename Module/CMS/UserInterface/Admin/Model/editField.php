<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'字段';
$url = $this->url('./admin/model/option');
$js = <<<JS
bindField('{$url}');
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/model/save_field')?>
    <?=Form::text('name', true)?>
    <?=Form::text('field', true)?>
    <?=Form::radio('is_main', ['否', '是'])?>
    <?php if($model->is_system < 1):?>
    <?=Form::select('type', $model->type_list)?>
    <div class="option-box">

    </div>
    <?=Form::radio('is_required', ['非必填', '必填'])?>
    <?=Form::text('length')?>
    <?=Form::text('match')?>
    <?=Form::text('tip_message')?>
    <?=Form::text('error_message')?>
    <?=Form::text('position')?>
    <?php endif;?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="model_id" value="<?=$model->model_id?>">
<?=Form::close('id')?>