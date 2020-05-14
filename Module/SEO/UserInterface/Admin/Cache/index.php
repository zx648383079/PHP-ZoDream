<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '缓存管理';
?>
<?=Form::open('./@admin/cache/clear')?>

    <?=Form::checkbox('store[]', ['default' => '默认', 'auth' => '用户', 'pages' => '页面'])->label('缓存区')?>

    <button type="submit" class="btn btn-success">清除选中</button>
    <input type="hidden" name="store[]" value="">
    <a href="<?=$this->url('./@admin/cache/clear')?>" data-type="del" data-tip="确认清除全部缓存" class="btn btn-danger">清除全部缓存</a>
<?= Form::close('id') ?>