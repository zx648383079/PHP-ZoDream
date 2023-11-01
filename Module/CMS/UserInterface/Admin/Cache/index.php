<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '缓存管理';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>一些后台操作不生效时，可以使用此功能进行强制更新页面</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<?=Form::open('./@admin/cache/clear')?>

    <?=Form::checkbox('store[]', array_column($storeItems, 'name', 'value'))->label('缓存区')?>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">清除选中</button>
        <a href="<?=$this->url('./@admin/cache/clear')?>" data-type="del" data-tip="确认清除全部缓存" class="btn btn-danger">清除全部缓存</a>
    </div>
    
    <input type="hidden" name="store[]" value="">
    
<?= Form::close('id') ?>