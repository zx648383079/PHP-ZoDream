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
                    <span>未完成订单（1）</span>
                    <a href="">查看全部订单</a>
                </div>
                <div class="panel-body">
                    <div class="order-item">
                        <div>
                            <img src="http://yanxuan.nosdn.127.net/9014a75315685c0ecccece8583fdba68.png?imageView&thumbnail=100x100&quality=95" alt="">
                        </div>
                        <div>包裹1</div>
                        <div>待付款</div>
                        <div>11</div>
                        <div>
                            <a href="" class="btn">付款</a>
                            <a href="">查看详情</a>
                            <a href="">取消订单</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
