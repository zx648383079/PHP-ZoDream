<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '添加商品详情';
?>
<div class="search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./admin/activity/seckill/create')?>">设置商品</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th class="auto-hide">商品价格</th>
        <th>秒杀价格</th>
        <th>秒杀数量</th>
        <th>限购数量</th>
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
            <td>
                <input type="text">
            </td>
            <td>
                <input type="text">
            </td>
            <td>
                <input type="text">
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/activity/seckill/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>