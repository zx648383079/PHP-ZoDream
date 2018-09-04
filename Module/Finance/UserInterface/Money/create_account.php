<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '资金账户';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './money/save_account')?>
    <?=Form::text('name', true)?>
    <?=Form::text('money')?>
    <?=Form::text('frozen_money')?>
    <?=Form::textarea('remark')?>
    <div class="input-group">
        <label>
            <input value="1" name="status" type="checkbox" <?=$model->status || $model->id < 1 ? 'checked': ''?>> 是否启用
        </label>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>
