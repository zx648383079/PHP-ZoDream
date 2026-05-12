<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '联动项列表';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>当前路径：
            <b>[<?= $model->name ?>]</b>
            <?php if($parent):?>
            <?= $parent->full_name ?>
            <?php endif;?>
        </li>
        <li>多语言只能在编辑中切换；每一级都必须一一对应，不然无法添加；未绑定的自动根据名称进行绑定</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

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
            <th>子项个数</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td class="left"><?=$item['name']?></td>
                <td><?=$item['children_count']?></td>
                <td>
                    <div class="btn-group toggle-icon-text">
                        <a class="btn btn-info" href="<?=$this->url('./@admin/linkage/data', ['id' => $model->id, 'parent_id' => $item->id])?>" title="管理下一级子项列表">
                            <span>下一级</span>
                            <i class="fa fa-arrow-right"></i>
                        </a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/linkage/edit_data', ['id' => $item->id])?>" title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/linkage/delete_data', ['id' => $item->id])?>" title="删除此项">
                            <span>删除</span>
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
    <?php if($model_list->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>
</div>

