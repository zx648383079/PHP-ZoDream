<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Shop\Domain\Model\OrderModel;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '订单详情';
$status_list = [
    [
        '待付款',
        [OrderModel::STATUS_UN_PAY, OrderModel::STATUS_PAID_UN_SHIP, OrderModel::STATUS_SHIPPED, OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH],
        $order->created_at
    ],
    [
        '待发货',
        [OrderModel::STATUS_PAID_UN_SHIP, OrderModel::STATUS_SHIPPED, OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH],
        $order->pay_at
    ],
    [
        '待收货',
        [OrderModel::STATUS_SHIPPED, OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH],
        $order->shipping_at
    ],
    [
        '待评价',
        [OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH],
        $order->receive_at
    ],
    [
        '已完成',
        [OrderModel::STATUS_FINISH],
        $order->finish_at
    ]
];
$js = <<<JS
bindOperate();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<div class="progress-bar">
    <div>
        <span></span>
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
            <div class="col-md-3">
                <p>订单号：<?=$order->series_number?></p>
                <p>订单状态：<?=$order->status_label?></p>
                <p>付款时间：<?=$order->pay_at?></p>
            </div>
            <div class="col-md-3">
                <p>商品总额：<?=$order->goods_amount?></p>
                <p>运费金额：<?=$order->shipping_fee?></p>
                <p>应付金额：<?=$order->order_amount?></p>
            </div>
            <div class="col-md-3">
                <p>是否需要发票：否</p>
                <?php if($address):?>
                <p>联系方式：<?=$address->tel?></p>
                <?php endif;?>
            </div>
            <div class="col-md-3">
                <p>优惠信息</p>
                <p>促销优惠：-10</p>
                <p>积分抵扣：-10</p>
            </div>
        </div>
        <?php if($order->status == OrderModel::STATUS_PAID_UN_SHIP):?>
        <div class="text-center">
            <a href="<?=$this->url('./admin/order/shipping', ['id' => $order->id])?>" class="btn">我要发货</a>
        </div>
        <?php endif;?>
    </div>
</div>
<table class="table-hover">
    <thead>
        <tr>
            <th>商品名称</th>
            <th>货号</th>
            <th>单价</th>
            <th>数量</th>
            <th>原价</th>
            <th>状态</th>
            <th>售后</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($goods_list as $goods):?>
        <tr>
            <td>
                <img src="<?=$goods->thumb?>" alt="" width="50" height="50" align="left">
                <a href=""><?=$goods->name?></a> <br>
                <small>白色</small>
            </td>
            <td><?=$goods->series_number?></td>
            <td>
                <span class="text-red"><?=$goods->price?></span>
            </td>
            <td>
                <?=$goods->number?>
            </td>
            <td>
                <span class="text-red"><?=$goods->total?></span>
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
    <small>
    配送费用：<span class="text-red"><?=$order->shipping_fee?></span>&nbsp;&nbsp;&nbsp;&nbsp;
    商品总额：<span class="text-red"><?=$order->goods_amount?> </span>
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
                <?=$order->remark?>
            </div>
        </div>
        <?php endif;?>
        <div class="panel">
            <div class="panel-header">
            <i class="fa fa-edit"></i>    
            备注信息</div>
            <div class="panel-body">
            <?=Form::open($order, './admin/order/save')?>
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
                收货信息
            </div>
            <div class="panel-body">
                <table class="table-left">
                    <tbody>
                    <tr>
                        <th style="width:30%">会员用户名:</th>
                        <td><?=$user->name?></td>
                    </tr>
                    <?php if($address):?>
                    <tr>
                        <th>收货人:</th>
                        <td><?=$address->name?></td>
                    </tr>
                    <tr>
                        <th>收货人手机:</th>
                        <td><?=$address->tel?></td>
                    </tr>
                    <tr>
                        <th>所在地:</th>
                        <td><?=$address->region_name?></td>
                    </tr>
                    <tr>
                        <th>收货地址:</th>
                        <td><?=$address->address?></td>
                    </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
<?php if(in_array($order->status, [OrderModel::STATUS_SHIPPED, OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH])):?>
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
            <i class="fa fa-box"></i>    
            配送信息</div>
            <div class="panel-body">
                <table class="table-left">
                    <tbody>
                        <tr>
                            <th style="width:50%">物流公司:</th>
                            <td><?=$delivery->shipping_name?></td>
                        </tr>
                        <tr>
                            <th>配送单号:</th>
                            <td><?=$delivery->logistics_number?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
            <i class="fa fa-file"></i>    
            发票信息</div>
            <div class="panel-body">
                <table class="table-left">
                    <tbody>
                        <tr>
                            <th style="width:50%">发票类型:</th>
                            <td>普通发票</td>
                        </tr>
                        <tr>
                            <th>发票抬头:</th>
                            <td>个人</td>
                        </tr>
                        <tr>
                            <th>发票内容:</th>
                            <td>由商家直接开具</td>
                        </tr>
                        <tr>
                            <th>公司名:</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>公司登记号:</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>公司地址:</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>公司电话:</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>银行开户名:</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>银行账号:</th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
