<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Bot\Domain\Model\EditorTemplateModel;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑图文模板';
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>编辑图文模板</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<?=Form::open($model, './@admin/template/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('type', EditorTemplateModel::$type_list)?>
    <?=Form::select('category', ['不限'])?>
    <?=Form::textarea('content', true)?>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>