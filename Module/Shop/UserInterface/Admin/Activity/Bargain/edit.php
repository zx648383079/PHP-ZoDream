<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '砍价';
?>
<h1><?=$this->title?></h1>
<?=Form::open('./admin/activity/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <div class="input-group">
        <label for="start_at">单次砍价范围</label>
        <div class="">
            <input type="text" id="min" class="form-control " name="min" placeholder="请输入最低砍价">
            ~
            <input type="text" id="max" class="form-control " name="max" placeholder="请输入最高砍价">
        </div>
    </div>

    <?=Form::text('砍价次数')?>
    <?=Form::text('商品件数')?>

    <?=Form::text('运费')?>

    <?=Form::text('start_at', true)?>
    <?=Form::text('end_at', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>