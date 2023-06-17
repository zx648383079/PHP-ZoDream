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
        <?=Form::text('category_template', true)->tip('栏目页，文件夹为Category')?>
        <?=Form::text('list_template', true)->tip('栏目文章搜索页，文件夹为Category')?>
        <?=Form::text('show_template', true)->tip('文章详情页，文件夹为Content')?>
    </div>
    <div class="form-box">
        <?=Form::checkbox('setting[is_show]')->value($model->setting('is_show'))->label('显示')->tip('允许显示在前台')?>
        <?=Form::checkbox('setting[is_only]')->value($model->setting('is_only'))->label('唯一')->tip('是否保持用户唯一')?>
        <?=Form::checkbox('setting[is_extend_auth]')->value($model->setting('is_extend_auth'))->label('是否继承拓展用户表单')->tip('在会员注册时需要填写此表单数据')?>
        <?=Form::checkbox('setting[open_captcha]')->value($model->setting('open_captcha'))->label('验证码')->tip('是否开启验证码')?>
        <?=Form::text('setting[form_template]')->value($model->setting('form_template'))->label('表单模板')->tip('表单填写的模板，文件夹为Content')?>
        <?=Form::text('setting[show_template]')->value($model->setting('show_template'))->label('显示模板')->tip('表单内容显示的模板，文件夹为Content')?>
    </div>
    <?=Form::text('position')?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?=Form::close('id')?>