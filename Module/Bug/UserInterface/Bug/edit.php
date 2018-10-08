<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = 'BUG';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './bug/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('type', $model->type_list, true)?>
    <?=Form::text('uri')?>
    <?=Form::text('grade')?>
    <?=Form::text('related')?>
    <?=Form::textarea('description')?>
    <?=Form::textarea('check_rule')?>
    <?=Form::textarea('solution')?>
    <?=Form::text('source')?>
    <?=Form::radio('status', ['否', '是'])?>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>