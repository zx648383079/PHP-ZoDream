<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\WeChat\Domain\Model\MediaModel;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑媒体资源';
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>编辑媒体资源</li>
    </ul>
    <span class="toggle"></span>
</div>

<?=Form::open($model, './@admin/media/save')?>
    <?=Form::text('title', true)?>
    <?=Form::select('type', MediaModel::$types)?>
    <?=Form::select('material_type', MediaModel::$materialTypes)?>
    <?=Form::file('content', true)?>
    
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>