<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '应用';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './platform/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('type', $model->type_list, true)?>
    <?=Form::text('domain')?>
    <?php if ($model->id):?>
        <?=Form::text('appid', ['readonly' => true])?>
        <?=Form::text('secret', ['readonly' => true])?>
        <?=Form::select('sign_type', $model->sign_type_list, true)?>
    <?php endif;?>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>