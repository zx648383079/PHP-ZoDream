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
                        <img src="http://yanxuan.nosdn.127.net/c3a03895c73694d922310c76e4915cdb.png?imageView&thumbnail=76x76&quality=95" alt="">
                    </div>
                    <div>111111</div>
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
        </div>
    </div>
</div>
