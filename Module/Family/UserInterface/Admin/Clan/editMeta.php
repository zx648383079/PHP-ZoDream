<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '附录';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/clan/save_meta')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('content')?>
    <?=Form::text('author')?>
    <?=Form::text('position')?>
    <?=Form::text('modify_at')?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="clan_id" value="<?=$model->clan_id?>">
<?= Form::close('id') ?>