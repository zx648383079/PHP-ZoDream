<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '理财产品列表';
?>
    <div class="search">
        <a class="btn btn-success pull-right" href="<?=$this->url('./money/add_product')?>">新增产品</a>
    </div>
    
    <hr/>
    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>形态名称</th>
            <th>状态</th>
            <th>说明</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><button type="button" class="btn <?=$item->status ==1 ? 'btn-success' : 'btn-danger' ?> btn-xs">
                <?=$item->status == 1 ? '启用' : '禁用' ?>
            </button></td>
                <td><?=$item->remark?></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                   
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./money/edit_product', ['id' => $item->id])?>">编辑</a>
                        <?php if($item->status == 0):?>
                            <a class="btn btn-success btn-xs" data-type="post" href="<?=$this->url('./money/change_product', ['id' => $item->id])?>">启用</a>
                        <?php else: ?>
                            <a class="btn btn-danger btn-xs" data-type="post" href="<?=$this->url('./money/change_product', ['id' => $item->id])?>">禁用</a>
                        <?php endif?>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./money/delete_product', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>