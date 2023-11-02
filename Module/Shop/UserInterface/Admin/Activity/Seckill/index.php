<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '秒杀活动列表';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <div class="btn-group pull-right">
            <a class="btn btn-info" href="<?=$this->url('./@admin/activity/seckill/create')?>">新增秒杀活动</a>
            <a class="btn btn-success" href="<?=$this->url('./@admin/activity/seckill/time')?>">秒杀时间列表</a>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
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
                <?=$this->time($item->start_at)?>
                </td>
                <td class="auto-hide">
                <?=$this->time($item->end_at)?>
                </td>
                <td>
                    开启/关闭
                </td>
                
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/activity/seckill/time', ['id' => $item->id])?>">设置商品</a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/activity/seckill/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/activity/seckill/delete', ['id' => $item->id])?>">删除</a>
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