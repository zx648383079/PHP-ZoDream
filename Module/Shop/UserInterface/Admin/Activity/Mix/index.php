<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '组合活动列表';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/activity/mix/create')?>">新增组合</a>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>组合明细</th>
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
                    <?php foreach($item->mix_configure['goods'] as $arg):?>
                    <p class="mix-item">
                        <a href="<?=$this->url('./admin/goods/edit', ['id' => $arg['goods_id']])?>"><?=$arg['goods']['name']?></a>
                        <span class="amount">*<?=$arg['amount']?></span>
                    </p>
                    <?php endforeach;?>
                </td>
                <td class="auto-hide">
                    <?=$this->time($item->start_at)?>
                </td>
                <td>
                    <?=$this->time($item->end_at)?>
                </td>
                
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/activity/mix/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/activity/mix/delete', ['id' => $item->id])?>">删除</a>
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