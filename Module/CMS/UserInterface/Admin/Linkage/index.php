<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '联动菜单列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/linkage/create')?>">新增联动菜单</a>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th>语言</th>
            <th>项个数</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td><?=$item['name']?></td>
                <td><?=$item['language']?></td>
                <td><?=$item['data_count']?></td>
                <td>
                   <div class="btn-group">
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/linkage/data', ['id' => $item->id])?>">联动项</a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/linkage/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/linkage/delete', ['id' => $item->id])?>" data-tip="联动菜单是所有站点公用的，确定删除？">删除</a>
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

