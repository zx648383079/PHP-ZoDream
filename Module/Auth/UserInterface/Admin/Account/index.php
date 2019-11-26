<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = '个人资料';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/account/update')?>
    <?=Form::text('name', true)?>
    <?=Form::email('email', true)?>
    <?=Form::radio('sex', $model->sex_list)?>
    <?=Form::file('avatar')?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close() ?>