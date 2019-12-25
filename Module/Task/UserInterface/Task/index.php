<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Task\Domain\Model\TaskModel;
/** @var $this View */
$this->title = 'ZoDream';
?>
   <div class="search">
        <a class="btn btn-success pull-right" href="<?=$this->url('./task/create')?>">新增任务</a>
    </div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>任务</th>
            <th>时长</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->time?></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <?php if($item->status >= TaskModel::STATUS_NONE):?>
                        <a class="btn btn-danger" data-type="del" data-tip="确认结束任务？" href="<?=$this->url('./task/delete', ['id' => $item->id, 'stop' => 1])?>">结束</a>
                        <?php endif;?>
                       <a class="btn btn-default btn-xs" href="<?=$this->url('./task/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./task/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>