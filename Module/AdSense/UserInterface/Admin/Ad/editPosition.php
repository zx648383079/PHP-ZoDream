<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '广告位';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/ad/save_position')?>
    <?=Form::text('name', true)?>
    <?=Form::text('code', true)?>
    <?=Form::switch('auto_size')?>
    <?=Form::radio('source_type', ['本地', '第三方广告'])?>
    <?=Form::text('width')?>
    <?=Form::text('height')?>
    <?=Form::switch('status')?>
    <?=Form::textarea('template')?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>