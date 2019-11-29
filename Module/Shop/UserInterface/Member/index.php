<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Shop';
$this->header_tpl = './user_header';
?>

<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div>
            <div class="user-box">
                <div class="user-info">
                    <div class="avatar">
                        <img src="<?=$user->avatar?>" alt="">
                    </div>
                    <div><?=$user->name?></div>
                    <div class="grade-box">
                        <a href="">成长值 >></a>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Description</div>
                        </div>
                    </div>
                </div>
                <div class="count-info">
                    <div>我的红包</div>
                    <div>¥0.00</div>

                    <div>优惠券</div>
                    <div>0张</div>

                    <div>可兑返利</div>
                    <div>¥0.00</div>

                    <div>礼品卡</div>
                    <div>¥0.00</div>

                    <div>我的积分</div>
                    <div>¥0.00</div>

                    <div>待领礼包</div>
                    <div>¥0.00</div>
                </div>
            </div>

            <div class="panel un-order-box">
                <div class="panel-header">
                    <span>未完成订单（<?=$order_list->getTotal()?>）</span>
                    <a href="<?=$this->url('./order')?>">查看全部订单</a>
                </div>
                <div class="panel-body">
                    <?php foreach($order_list as $order):?>
                    <div class="order-item">
                        <div class="goods-img">
                            <?php if($order->goods):?>
                            <img src="<?=$order->goods[0]->thumb?>" alt="">
                            <?php endif;?>
                        </div>
                        <div>包裹1</div>
                        <div><?=$order->status_label?></div>
                        <div><?=$order->order_amount?></div>
                        <div>
                            <?php if($order->status == 10):?>
                            <p><a href="<?=$this->url('./cashier/pay', ['id' => $order->id])?>" class="btn">付款</a></p>
                            <?php elseif($order->status == 40):?>
                            <p><a data-type="ajax" href="<?=$this->url('./order/receive', ['id' => $order->id])?>" class="btn">签收</a></p>
                            <?php endif;?>
                            <p><a href="<?=$this->url('./order/detail', ['id' => $order->id])?>">查看详情</a></p>
                            <?php if($order->status == 10):?>
                            <p><a data-type="del" data-tip="确定要取消订单?" href="<?=$this->url('./order/cancel', ['id' => $order->id])?>">取消订单</a></p>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>
