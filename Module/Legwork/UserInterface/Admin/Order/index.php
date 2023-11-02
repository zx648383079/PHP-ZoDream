<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '服务订单列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="series_number">订单号</label>
                <input type="text" class="form-control" name="series_number" id="series_number" placeholder="订单号">
            </div>
            <div class="input-group">
                <label class="sr-only" for="log_id">支付流水号</label>
                <input type="text" class="form-control" name="log_id" id="log_id" placeholder="支付流水号">
            </div>
            <div class="input-group">
                <label>状态</label>
                <select name="status" class="form-control">
                    <option value="0">全部</option>
                    <?php foreach($status_list as $key => $item):?>
                    <option value="<?=$key?>" <?=$status == $key ? 'selected': '' ?>><?=$item?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <table class="table  table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>服务名</th>
            <th>数量</th>
            <th>状态</th>
            <th>下单时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->service->name?></td>
                <td><?=$item->amount?></td>
                <td><?=$item->status_label?></td>
                <td><?=$item->created_at?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/order/info', ['id' => $item->id])?>">查看</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/order/delete', ['id' => $item->id])?>">删除</a>
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