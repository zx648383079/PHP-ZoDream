<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '账户安全';
$this->extend('../layouts/header');
?>
<div class="has-header">
    <div class="account-box">
        <div class="line-item">
            <span><i class="fab fa-qq"></i>QQ</span>
            <span>已绑定</span>
            <i class="fa fa-chevron-right"></i>
        </div>
        <div class="line-item">
            <span> <i class="fab fa-weixin"></i>微信</span>
            <span>未绑定</span>
            <i class="fa fa-chevron-right"></i>
        </div>
        <div class="line-item">
            <span> <i class="fab fa-alipay"></i>支付宝</span>
            <span>未绑定</span>
            <i class="fa fa-chevron-right"></i>
        </div>
        <div class="line-item">
            <span><i class="fab fa-weibo"></i>微博</span>
            <span>未绑定</span>
            <i class="fa fa-chevron-right"></i>
        </div>
        <div class="line-item">
            <span><i class="fab fa-paypal"></i>PayPal</span>
            <span>未绑定</span>
            <i class="fa fa-chevron-right"></i>
        </div>
        <div class="line-item">
            <span><i class="fab fa-github"></i>Github</span>
            <span>未绑定</span>
            <i class="fa fa-chevron-right"></i>
        </div>
        <div class="line-item">
            <span><i class="fab fa-google"></i>Google</span>
            <span>未绑定</span>
            <i class="fa fa-chevron-right"></i>
        </div>
    </div>
</div>