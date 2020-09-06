<?php
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'站点';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/site/save')?>
    <?=Form::text('title', true)?>
    <?=Form::select('theme', [$themes, 'description', 'name'])?>
    <?=Form::select('match_type', ['域名', '路径'])?>
    <?=Form::text('match_rule')?>
    <?=Form::file('logo')?>
    <?=Form::text('keywords')?>
    <?=Form::textarea('description')?>
   
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?=Form::close('id')?>