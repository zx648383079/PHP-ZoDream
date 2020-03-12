<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '免费试用';
?>
<h1><?=$this->title?></h1>
<?=Form::open('./@admin/activity/save')?>
    <?=Form::text('goods_id', true)?>
    <?=Form::text('试用数量', true)?>
    <?=Form::text('start_at', true)?>
    <?=Form::text('end_at', true)?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>