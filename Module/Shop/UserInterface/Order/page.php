<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach($order_list as $order):?>
    <div class="panel">
        <div class="panel-header order-item-header">
            <span class="time">下单时间：<?=$order->created_at?></span>
            <span class="number">订单号：<?=$order->series_number?></span>
            <a href="">
                <i class="fa fa-trash"></i>
            </a>
        </div>
        <div class="panel-body">
            <?php foreach($order->goods as $goods):?>
            <div class="order-item">
                <div class="goods-img">
                    <img src="<?=$goods->thumb?>" alt="">
                </div>
                <div class="name"><?=$goods->name?></div>
                <div class="status">
                    <span><?=$order->status_label?></span>
                    <a data-type="ajax" href="<?=$this->url('./order/repurchase', ['id' => $order->id])?>">再次购买</a>
                </div>
                <div class="price">
                    <?=$goods->price?>
                    <p>（含运费：¥0.00元）</p>
                </div>
                <div class="actions">
                    <a href="<?=$this->url('./cashier/pay', ['id' => $order->id])?>" class="btn">付款</a>
                    <a href="<?=$this->url('./order/detail', ['id' => $order->id])?>">查看详情</a>
                    <?php if($order->status == 10):?>
                    <a data-type="del" data-tip="确定要取消订单?" href="<?=$this->url('./order/cancel', ['id' => $order->id])?>">取消订单</a>
                    <?php endif;?>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
<?php endforeach;?>
<?=$order_list->getLink()?>