<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Shop\Domain\Models\AttributeModel;
/** @var $this View */
$this->title = '属性';
?>
   <div class="search">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/attribute/create', ['group_id' => $group_id])?>">新增属性</a>
    </div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>属性名称</th>
            <th>商品类型</th>
            <th>属性是否可选</th>
            <th>属性值的录入方式</th>
            <th>可选值列表</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->group->name?></td>
                <td><?=AttributeModel::$type_list[$item->type]?></td>
                <td><?=['手工录入', '列表选择'][$item->input_type]?></td>
                <td><?=$item->default_value?></td>
                <td><?=$item->position?></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/attribute/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/attribute/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>