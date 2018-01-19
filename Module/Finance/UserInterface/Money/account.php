<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '资金账户列表';

$this->extend('layouts/header');
?>
    <div class="search">
        <a class="btn btn-success pull-right" href="<?=$this->url('./money/add_account')?>">新增产品</a>
    </div>

    <hr/>
    <table class="table  table-bordered well">
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
        <?php foreach($account_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->money?></td>
                <td><?=$item->frozen_money?></td>
                <td><button type="button" class="btn <?=$item->status ==1 ? 'btn-success' : 'btn-danger' ?> btn-xs">
                        <?=$item->status == 1 ? '启用' : '禁用' ?>
                    </button></td>
                <td><?=$item->remark?></td>
                <td>
                    <div class="btn-group  btn-group-xs">

                        <a class="btn btn-default btn-xs" href="<?=$this->url('./money/edit_account', ['id' => $item->id])?>">编辑</a>
                        <?php if($item->status == 0):?>
                            <a class="btn btn-success btn-xs" data-type="post" href="<?=$this->url('./money/change_account', ['id' => $item->id])?>">启用</a>
                        <?php else: ?>
                            <a class="btn btn-danger btn-xs" data-type="post" href="<?=$this->url('./money/change_account', ['id' => $item->id])?>">禁用</a>
                        <?php endif?>
                        <a class="btn btn-danger" data-type="post" href="<?=$this->url('./money/delete_account', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php
$this->extend('layouts/footer');
?>