<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '团购活动列表';
?>
<div class="page-search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/activity/group_buy/create')?>">新增团购活动</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th class="auto-hide">结束时间</th>
        <th class="auto-hide">保证金</th>
        <th>限购</th>
        <th class="auto-hide">订购商品</th>
        <th class="auto-hide">订单</th>
        <th class="auto-hide">当前价格</th>
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

            </td>
            <td class="auto-hide">
                
            </td>
            <td class="auto-hide">
                
            </td>
            <td class="auto-hide">
            
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/activity/coupon/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/activity/conpon/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>