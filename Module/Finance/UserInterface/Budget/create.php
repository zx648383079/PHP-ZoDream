<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'生活预算';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './budget/save')?>
    <?=Form::text('name', true)?>
    <div class="input-group">
        <label>预算(元)</label>
        <input name="budget" type="text" class="form-control" value="<?=$model->budget ?: 1000?>" />
    </div>
    <?=Form::select('cycle', ['一次', '每天', '每周', '每月', '每年'])?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>