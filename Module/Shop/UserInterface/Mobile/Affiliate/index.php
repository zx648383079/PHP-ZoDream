<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的推荐';
$url = $this->url('./mobile/affiliate/rule');
$header_btn = <<<HTML
<a class="right-text" href="{$url}">
    规则
</a>
HTML;

$this->extend('../layouts/header', compact('header_btn'));
?>
<div class="has-header">
    <div class="affiliate-header">
        <div>
            <p>已推荐</p>
            <div class="money">0.00</div>
        </div>
        <div>
            <p>已分成</p>
            <div class="money">0.00</div>
        </div>
    </div>

    <div class="menu-list">
        <a href="<?=$this->url('./mobile/affiliate/order')?>">
            <i class="fa fa-coins" aria-hidden="true"></i>
            推荐的订单
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="<?=$this->url('./mobile/affiliate/user')?>">
            <i class="fa fa-users" aria-hidden="true"></i>
            推荐的会员
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="<?=$this->url('./mobile/affiliate/share')?>">
            <i class="fa fa-share" aria-hidden="true"></i>
            我的分享
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
    </div>
</div>
