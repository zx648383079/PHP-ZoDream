<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '订单列表';
?>
<div class="search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="series_number">订单号</label>
            <input type="text" class="form-control" name="series_number" id="series_number" placeholder="订单号">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./admin/order/create')?>">新增订单</a>
</div>

<table class="table  table-hover">
    <thead>
    <tr>
        <th>订单号</th>
        <th>会员</th>
        <th>金额</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->series_number?></td>
            <td><?=$item->user->name?></td>
            <td>
            <?=$item->goods_amount?>
            </td>
            <td>
                <?=$item->status?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./admin/order/edit', ['id' => $item->id])?>">编辑</a>
                    <?php if($item->status <= 10):?>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/order/delete', ['id' => $item->id])?>">删除</a>
                    <?php endif;?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>