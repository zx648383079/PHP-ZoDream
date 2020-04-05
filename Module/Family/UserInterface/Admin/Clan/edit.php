<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '族谱';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/clan/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>