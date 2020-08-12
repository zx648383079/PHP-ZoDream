<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Legwork\Domain\Model\OrderModel;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '订单详情';
$status_list = [
    [
        '待支付',
        [OrderModel::STATUS_UN_PAY, OrderModel::STATUS_PAYING, OrderModel::STATUS_TAKING_UN_DO, OrderModel::STATUS_PAID_UN_TAKING, OrderModel::STATUS_TAKEN, OrderModel::STATUS_FINISH],
        $order->created_at
    ],
    [
        '待接单',
        [OrderModel::STATUS_PAID_UN_TAKING, OrderModel::STATUS_TAKING_UN_DO, OrderModel::STATUS_TAKEN, OrderModel::STATUS_FINISH],
        $order->pay_at
    ],
    [
        '待取件',
        [OrderModel::STATUS_TAKING_UN_DO, OrderModel::STATUS_TAKEN, OrderModel::STATUS_FINISH],
        $order->taking_at
    ],
    [
        '待评价',
        [OrderModel::STATUS_TAKEN, OrderModel::STATUS_FINISH],
        $order->taken_at
    ],
    [
        '已完成',
        [OrderModel::STATUS_FINISH],
        $order->finish_at
    ]
];
$statusIndex = 0;
foreach($status_list as $k => $item) {
    if(in_array($order->status, $item[1])) {
        $statusIndex = $k;
    }
}
$js = <<<JS
bindOperate();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<div class="progress-bar">
    <div>
        <span style="width: <?=$statusIndex * 25?>%;"></span>
    </div>
    <?php foreach($status_list as $item):?>
    <?php if(in_array($order->status, $item[1])):?>
    <span class="active"><?=$item[0]?>
        <i><?=is_numeric($item[2]) ? $this->time($item[2]) : $item[2]?> </i>
    </span>
    <?php else:?>
    <span><?=$item[0]?></span>
    <?php endif;?>
    <?php endforeach;?>
</div>
<div class="panel">
    <div class="panel-header">
        <i class="fa fa-globe"></i> 订单信息
        <small class="pull-right">
            下单时间：<?=$order->created_at?>
        </small>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <p>订单号：<?=$order->id?></p>
                <p>订单状态：<?=$order->status_label?></p>
                <p>付款时间：<?=$order->pay_at?></p>
            </div>
            <div class="col-md-6">
                <p>应付金额：<?=$order->order_amount?></p>
            </div>
        </div>
        <?php if($order->status == OrderModel::STATUS_PAID_UN_TAKING):?>
        <div class="text-center">
            <a href="<?=$this->url('./@admin/order/shipping', ['id' => $order->id])?>" class="btn">我要接单</a>
            <a href="<?=$this->url('./@admin/order/refund', ['id' => $order->id])?>" class="btn">退款</a>
        </div>
        <?php endif;?>
    </div>
</div>
<table class="table-hover">
    <thead>
        <tr>
            <th>服务名称</th>
            <th>货号</th>
            <th>单价</th>
            <th>数量</th>
            <th>原价</th>
            <th>状态</th>
            <th>售后</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach([$order] as $goods):?>
        <tr>
            <td>
                <img src="<?=$goods->service->thumb?>" alt="" width="50" height="50" align="left">
                <a href=""><?=$goods->service->name?></a>
            </td>
            <td><?=$goods->service_id?></td>
            <td>
                <span class="text-red"><?=$goods->service->price?></span>
            </td>
            <td>
                <?=$goods->amount?>
            </td>
            <td>
                <span class="text-red"><?=$goods->order_amount?></span>
            </td>
            <td>
                <?=$goods->status_label?>
            </td>
            <td>
                未申请售后
            </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="text-right">
    商品总额：<span class="text-red"><?=$order->order_amount?> </span>
  </small>
</div>

<div class="row">
    <div class="col-md-6">
        <?php if($order->remark):?>
        <div class="panel">
            <div class="panel-header">
            <i class="fa fa-bookmark"></i>    
            买家备注</div>
            <div class="panel-body">
                <?php foreach($order->remark as $item):?>
                   <p><?=$item['label']?>: <?=$item['value']?></p>
                <?php endforeach;?>
            </div>
        </div>
        <?php endif;?>
        <div class="panel">
            <div class="panel-header">
            <i class="fa fa-edit"></i>    
            备注信息</div>
            <div class="panel-body">
            <?=Form::open($order, './@admin/order/save')?>
                    <div class="remark-box">
                        <textarea name="remark" required></textarea>
                    </div>
                    <button type="button" class="btn" data-operate="remark">保存</button>
                    <?php if($order->status == OrderModel::STATUS_UN_PAY):?>
                    <button type="button" class="btn btn-danger" data-operate="pay">支付</button>
                    <?php endif;?>
                    <button type="button" class="btn btn-danger" data-operate="cancel">取消</button>
                    <input type="hidden" name="operate" valuue="remark">
            <?= Form::close('id') ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <i class="fa fa-map-marked"></i>
                会员信息
            </div>
            <div class="panel-body">
                <table class="table-left">
                    <tbody>
                    <tr>
                        <th style="width:30%">会员用户名:</th>
                        <td>
                        <?=$order->user ? $order->user->name : '[已删除]'?></td>
                    </tr>
                    <tr>
                        <th style="width:30%">接单人:</th>
                        <td>
                            <?php if($order->runner > 0):?>
                            <?=$order->runnerUser->name?>
                            <?php else:?>
                             [未接单]
                            <?php endif;?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
