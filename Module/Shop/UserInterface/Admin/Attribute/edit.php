<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Module\Shop\Domain\Models\AttributeModel;
/** @var $this View */
$this->title = '属性';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/attribute/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('group_id', [$type_list])?>
    <?=Form::radio('search_type', AttributeModel::$search_list)?>
    <?=Form::radio('type', AttributeModel::$type_list)->tip('选择"单选/复选属性"时，可以对商品该属性设置多个值，复选属性还能对不同属性值指定不同的价格加价，用户购买商品时需要选定具体的属性值。选择"唯一属性"时，商品的该属性值只能设置一个值，用户只能查看该值。')?>
    <?=Form::radio('input_type', ['手工录入', '从下面的列表中选择（一行代表一个可选值）'])?>
    <?=Form::textarea('default_value')?>
    <?=Form::text('position')?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>
