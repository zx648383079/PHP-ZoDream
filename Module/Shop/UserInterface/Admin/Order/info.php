<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '订单详情';
?>
<h1><?=$this->title?></h1>
<div class="progress-bar">
    <div>
        <span></span>
    </div>
    <span class="active">待付款
        <i>2018-01-12 18:12:12</i>
    </span>
    <span class="active">待发货
        <i>2018-01-12 18:12:12</i>
    </span>
    <span>待收货</span>
    <span>待评价</span>
    <span>已完成</span>
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
                <p>联系方式：<?=$address->tel?></p>
            </div>
            <div class="col-md-3">
                <p>优惠信息</p>
                <p>促销优惠：-10</p>
                <p>积分抵扣：-10</p>
            </div>
        </div>
        <div class="text-center">
            <a href="" class="btn">我要发货</a>
        </div>
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
                等待买家付款
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
        <div class="panel">
            <div class="panel-header">
            <i class="fa fa-bookmark"></i>    
            买家备注</div>
            <div class="panel-body">
                <?=$order->remark?>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
            <i class="fa fa-edit"></i>    
            备注信息</div>
            <div class="panel-body">
                <div class="remark-box">
                    <textarea name=""></textarea>
                </div>
                <button class="btn">保存</button>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
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
                            <td></td>
                        </tr>
                        <tr>
                            <th>配送单号:</th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
       
    </div>
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
