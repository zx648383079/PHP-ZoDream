<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '配送方式';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/shipping/save')?>
    <?=Form::text('name', true)?>
    <?=Form::file('thumb')?>
    <?=Form::select('code', $shipping_list, true)?>
    <?=Form::text('fee')?>
    <?=Form::textarea('description')?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>