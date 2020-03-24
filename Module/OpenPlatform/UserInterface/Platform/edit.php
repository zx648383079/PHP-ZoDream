<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '应用';
$js = <<<JS
bindEdit();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './platform/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('type', $model->type_list, true)?>
    <?=Form::text('domain')?>
    <?=Form::textarea('description')?>
    <?php if ($model->id && $model->status == 1):?>
        <?=Form::text('appid')->readonly(true)?>
        <?=Form::text('secret')->readonly(true)->size(40)?>
        <?=Form::select('sign_type', $model->sign_type_list, true)?>
        <?=Form::textarea('sign_key')->tip('请输入签名密钥或签名组成字段请用“+”链接,例如：appid+timestamp+secret')?>
        <?=Form::select('encrypt_type', $model->encrypt_type_list, true)?>
        <?=Form::textarea('public_key')?>
    <?php endif;?>

    <button type="submit" class="btn btn-success"><?=$model->id ? '确认保存' : '提交审核'?></button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>