<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '广告列表';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <div class="btn-group pull-right">
            <a class="btn btn-primary" href="<?=$this->url('./@admin/ad/position')?>">广告位管理</a>
            <a class="btn btn-success" href="<?=$this->url('./@admin/ad/create')?>">新增广告</a>
        </div>
        
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>广告名</th>
            <th title="点击数/展示数">统计</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td class="left">[<?= $item->position ? $item->position->name : '' ?>]<?=$item->name?></td>
                <td title="点击数/展示数">0/0</td>
                <td><?= $item->status > 0 ? '显示' : '关闭' ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/ad/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/ad/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
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