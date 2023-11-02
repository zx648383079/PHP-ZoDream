<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Module\Task\Domain\Model\TaskModel;
/** @var $this View */
$this->title = '任务';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './task/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::text('every_time')?>
    <?=Form::select('status', [
            TaskModel::STATUS_NONE => '开启',
            TaskModel::STATUS_RUNNING => '运行中',
            TaskModel::STATUS_PAUSE => '暂停',
            TaskModel::STATUS_COMPLETE => '已完成'])?>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>