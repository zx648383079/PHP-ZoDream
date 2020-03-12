<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '免费试用列表';
?>
<div class="page-search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/activity/free_trial/create')?>">新增免费试用</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th class="auto-hide">开始时间</th>
        <th class="auto-hide">结束时间</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td>
                <?=$item->name?>
            </td>
            <td class="auto-hide">
                
            </td>
            <td class="auto-hide">
                
            </td>
            <td>
                开启/关闭
            </td>
            
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/activity/lottery/edit', ['id' => $item->id])?>">申请记录</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/activity/lottery/edit', ['id' => $item->id])?>">试用报告</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/activity/lottery/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/activity/lottery/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>