<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '属性类型';
?>
   <div class="page-search">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/attribute/create_group')?>">新增类型</a>
    </div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>商品类型名称</th>
            <th>类型分组</th>
            <th>属性数</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->goods_count?></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/attribute', ['group_id' => $item->id])?>">属性列表</a>
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/attribute/edit_group', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/attribute/delete_group', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>