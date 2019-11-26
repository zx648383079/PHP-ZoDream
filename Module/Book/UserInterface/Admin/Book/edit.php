<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '小说';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/book/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('cat_id', [$cat_list, 'real_name'], true)?>
    <?=Form::select('author_id', [$author_list], true)?>
    <?=Form::select('classify', $model->classify_list, true)?>
    <?=Form::text('source')?>
    <?=Form::file('cover')?>
    <?=Form::textarea('description')?>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>