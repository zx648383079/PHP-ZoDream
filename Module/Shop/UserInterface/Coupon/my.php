<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div>
            <div class="coupon-search">
                <div class="search-box">
                    <input type="text">
                    <button>兑换</button>
                </div>
                <a href="">如何兑换使用优惠券 >></a>
                <a href="<?=$this->url('./coupon')?>" class="btn">
                    <i class="fa fa-gift"></i>
                    领券中心
                </a>
            </div>
            <div class="tab-box">
                <div class="tab-header">
                    <div class="tab-item active">可使用</div>
                    <div class="tab-item">已使用</div>
                    <div class="tab-item">已失效</div>
                </div>
                <div class="tab-body">
                    <div class="tab-item active">
                        <ul class="coupon-box">
                            <li>
                                <div class="coupon-info">
                                    <div class="money">15</div>
                                    <div>
                                        <div>name</div>
                                        <div>2018.11.23-2018.11.23</div>
                                        <a href="">去使用</a>
                                    </div>
                                </div>
                                <div class="coupon-tip">
                                    <div class="part">
                                        <div>厨房配件专享；限时购、特价等特惠商...</div>
                                        <i class="fa"></i>
                                    </div>
                                    <div class="full">
                                    厨房配件专享；限时购、特价等特惠商品，处于新品期的商品及详情页标注不可用券的商品除外
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-item">
                        <div class="empty-box">
                            <div class="icon"></div>
                            <div class="tip">优惠券列表还是空的</div>
                        </div>
                    </div>
                    <div class="tab-item">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>