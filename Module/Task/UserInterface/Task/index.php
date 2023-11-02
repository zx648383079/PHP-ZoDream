<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Task\Domain\Model\TaskModel;
use Zodream\Helpers\Time;
/** @var $this View */
$this->title = 'ZoDream';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./task/create')?>">新增任务</a>
    </div>

    <table class="table  table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>任务</th>
            <th>时长</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td class="left"><?=$item->name?></td>
                <td><?=Time::hoursFormat($item->time)?></td>
                <td>
                    <div class="btn-group">
                        <?php if($item->status >= TaskModel::STATUS_NONE):?>
                        <a class="btn btn-info" data-type="del" data-tip="确认结束任务？" href="<?=$this->url('./task/delete', ['id' => $item->id, 'stop' => 1])?>">结束</a>
                        <?php endif;?>
                        <a class="btn btn-default" href="<?=$this->url('./task/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./task/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($items->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$items->getLink()?>
    </div>
</div>