<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '资金账户列表';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./money/add_account')?>">新增账户</a>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>账户</th>
            <th>可用资金</th>
            <th>冻结资金</th>
            <th>状态</th>
            <th>说明</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->money?></td>
                <td><?=$item->frozen_money?></td>
                <td><button type="button" class="btn <?=$item->status ==1 ? 'btn-success' : 'btn-danger' ?>">
                        <?=$item->status == 1 ? '启用' : '禁用' ?>
                    </button></td>
                <td><?=$item->remark?></td>
                <td>
                    <div class="btn-group">

                        <a class="btn btn-default" href="<?=$this->url('./money/edit_account', ['id' => $item->id])?>">编辑</a>
                        <?php if($item->status == 0):?>
                            <a class="btn btn-success" data-type="ajax" href="<?=$this->url('./money/change_account', ['id' => $item->id])?>">启用</a>
                        <?php else: ?>
                            <a class="btn btn-info" data-type="ajax" href="<?=$this->url('./money/change_account', ['id' => $item->id])?>">禁用</a>
                        <?php endif?>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./money/delete_account', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(empty($items)):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
</div>