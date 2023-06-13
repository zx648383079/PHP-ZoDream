<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '联动项列表';
?>

<div class="panel-container">
    <div class="page-search-bar">

        <div class="btn-group pull-right">
            <?php if($parent):?>
            <a class="btn btn-primary" href="<?=$this->url('./@admin/linkage/data', ['id' => $model->id, 'parent_id' => $parent->parent_id])?>">上一级</a>
            <?php else:?>
            <a class="btn btn-success" href="<?=$this->url('./@admin/linkage')?>">返回联动菜单</a>
            <?php endif;?>
            <a class="btn btn-info" href="<?=$this->url('./@admin/linkage/create_data', ['linkage_id' => $model->id, 'parent_id' => $parent_id])?>">新增联动项</a>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td><?=$item->name?></td>
                <td>
                    <a class="btn btn-default" href="<?=$this->url('./@admin/linkage/data', ['id' => $model->id, 'parent_id' => $item->id])?>">下一级</a>
                    <a class="btn btn-default" href="<?=$this->url('./@admin/linkage/edit_data', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/linkage/delete_data', ['id' => $item->id])?>">删除</a>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
    <?php if(empty($model_list)):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
</div>

