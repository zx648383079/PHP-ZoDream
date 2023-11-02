<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '生活预算';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <div class="btn-group pull-right">
            <a class="btn btn-default" href="<?=$this->url('./budget/add')?>">新增预算</a>
            <a class="btn btn-success" data-type="ajax" href="<?=$this->url('./budget/refresh')?>">刷新预算</a>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <td>名称</td>
            <td>预算(元)</td>
            <td>已花费(元)</td>
            <td>剩余(元)</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item): ?>
            <tr class="<?=$item['remain'] < 0 ? 'danger' : ''?>">
                <td class="left">
                    <a href="<?=$this->url('./budget/statistics', ['id' => $item['id']])?>" title="查看支出统计图">
                        <?=$item['name']?>
                    </a>
                </td>
                <td>
                    <?=$item['budget']?>
                </td>
                <td>
                    <?=$item['spent']?>
                </td>
                <td>
                    <?=$item['remain']?>
                </td>
                <td class="right">
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./income/add_log', ['budget_id' => $item['id']])?>">消费</a>
                        <a class="btn btn-primary" href="<?=$this->url('./budget/edit', ['id' => $item['id']])?>">编辑</a>
                        <a class="btn btn-danger" data-type="post" href="<?=$this->url('./budget/delete', ['id' => $item['id']])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach?>
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