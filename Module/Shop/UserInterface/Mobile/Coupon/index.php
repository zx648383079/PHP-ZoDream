<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '领券中心';
$this->extend('../layouts/header')
?>
<div class="has-header has-footer">
    <div class="scroll-nav">
        <ul>
            <?php foreach($cat_list as $item):?>
               <li>
                    <a href=""><?=$item->name?></a>
               </li>
            <?php endforeach;?>
        </ul>
        <a href="javascript:;" class="fa nav-arrow"></a>
    </div>

    <div>
        <?php foreach($coupon_list as $item):?>
            <div class="coupon-item">
                <div class="thumb">
                    <img src="<?=$item->thumb?>" alt="">
                </div>
                <div class="info">
                    <p><?=$item->name?></p>
                    <?php if($item->type > 0):?>
                    <dl class="discount">
                        <dd>8.8折</dd>
                        <dt>满168可用</dt>
                    </dl>
                    <?php else:?>
                    <div class="price">
                        <em>¥300</em>
                        满168可用
                    </div>
                    <?php endif;?>
                </div>
                <div class="action">
                    <span class="status-icon">立即<br>领取</span>
                    <i>剩余76%</i>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>

<footer class="tab-bar">
    <a href="<?=$this->url('./mobile/coupon')?>" class="active">
        <i class="fa fa-gift" aria-hidden="true"></i>
        领券
    </a>
    <a href="<?=$this->url('./mobile/coupon/my')?>">
        <i class="fa fa-user" aria-hidden="true"></i>
        我的优惠券
    </a>
</footer>