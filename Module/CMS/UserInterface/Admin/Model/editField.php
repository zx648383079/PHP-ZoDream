<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'字段';
$url = $this->url('./@admin/model/option');
$js = <<<JS
bindField('{$url}');
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/model/save_field')?>
    <?=Form::text('name', true)?>
    <?=Form::text('field', true)?>
    <?=Form::radio('is_main', ['否', '是'])?>
    <?php if($model->is_system < 1):?>
    <?=Form::select('type', $model->type_list)?>
    <div class="option-box">

    </div>
    <?=Form::radio('is_required', ['非必填', '必填'])?>
    <?=Form::radio('is_search', ['否', '是'])?>
    <?=Form::text('length')?>
    <?=Form::text('match')?>
    <?=Form::text('tip_message')?>
    <?=Form::text('error_message')?>
    <div class="input-group">
        <label>编辑组</label>
        <div class="tab-group">
            <?php foreach($tab_list as $key => $item):?>
            <span class="radio-label">
                <input type="radio" id="tab_name<?=$key?>" name="tab_name" value="<?=$item?>" <?=$item == $model->tab_name ? 'checked' : ''?>>
                <label for="tab_name<?=$key?>"><?=$item?></label>
            </span>
            <?php endforeach;?>
            <?php if(count($tab_list) < 5):?>
               <div class="add-box">
                   <input type="text">
                   <button type="button">添加</button>
               </div>
            <?php endif;?>
        </div>
    </div>
    <?=Form::text('position')?>
    <?php endif;?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
    <input type="hidden" name="model_id" value="<?=$model->model_id?>">
<?=Form::close('id')?>