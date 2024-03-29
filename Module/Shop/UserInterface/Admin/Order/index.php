<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Shop\Domain\Models\OrderModel;
/** @var $this View */
$this->title = '订单列表';
?>
<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="series_number">订单号</label>
                <input type="text" class="form-control" name="series_number" id="series_number" placeholder="订单号" value="<?=$this->text($series_number)?>">
            </div>
            <div class="input-group">
                <label class="sr-only" for="log_id">支付流水号</label>
                <input type="text" class="form-control" name="log_id" id="log_id" placeholder="支付流水号" value="<?=$this->text($log_id)?>">
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
        <a class="btn btn-success pull-right" data-type="ajax" href="<?=$this->url('./@admin/order/cron')?>" title="未支付订单释放库存">整理订单</a>
    </div>
</div>

<div class="order-box">
    <?php foreach($model_list as $item):?>
    <div class="order-item">
        <div class="item-header">
            <span><?=$item->updated_at?></span>
            <span>订单号：<?=$item->series_number?></span>
            <span>订单总额：<?=$item->total?></span>
            <span>状态：<?=$item->status_label?></span>
            <span>售后：无</span>
        </div>
        <div class="item-body">
            <div class="item-goods">
                <?php foreach($item->goods as $goods):?>
                   <div class="goods-item">
                        <div class="goods-img">
                            <img src="<?=$goods->thumb?>" alt="">
                        </div>
                        <div class="goods-info">
                            <h4><?=$goods->name?></h4>
                            <p>属性</p>
                            <span class="price"><?=$goods->price?></span>
                            <span class="amount"> x <?=$goods->amount?></span>
                        </div>
                   </div>
                <?php endforeach;?>
            </div>
            <div class="item-subtotal">
                <p>应付：
                <span><?=$item->order_amount?></span>
                </p>
                <p>运费：<?=$item->shipping_fee?></p>
            </div>
            <div class="item-user">
                <p>用户名：<?=$item->user->name?></p>
                <?php if($item->address):?>
                <p>收货人：<?=$item->address->name?></p>
                <p><?=$item->address->tel?></p>
                <?php endif;?>
            </div>
            <div class="item-actions">
                <a href="<?=$this->url('./@admin/order/info', ['id' => $item->id])?>">订单详情</a>
                <?php if($item->status == OrderModel::STATUS_UN_PAY):?>
                <a href="<?=$this->url('./@admin/order/info', ['id' => $item->id])?>">修改运费</a>
                <?php endif;?>
                <?php if($item->status == OrderModel::STATUS_SHIPPED):?>
                <a href="<?=$this->url('./@admin/order/info', ['id' => $item->id])?>">拒收</a>
                <?php endif;?>
                <?php if(in_array($item->status, [OrderModel::STATUS_UN_PAY, OrderModel::STATUS_CANCEL, OrderModel::STATUS_INVALID])):?>
                <a class="btn-danger" data-type="del" href="<?=$this->url('./@admin/order/delete', ['id' => $item->id])?>">删除</a>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if($model_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
<?php endif;?>
<div align="center">
    <?=$model_list->getLink()?>
</div>