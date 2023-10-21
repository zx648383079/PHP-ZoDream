<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Module\MessageService\Domain\Repositories\MessageProtocol;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑模板' : '新增模板';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/template/save')?>
    <?=Form::text('name', true)?>
    <?=Form::text('title', true)?>
    <?=Form::text('target_no')?>
    <?=Form::radio('type', [MessageProtocol::TYPE_TEXT => 'TEXT', MessageProtocol::TYPE_HTML => 'HTML'])?>
    <?=Form::textarea('content')?>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>