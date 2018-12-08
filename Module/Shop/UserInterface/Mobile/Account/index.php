<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的余额';
$url = $this->url('./mobile/account/log');
$header_btn = <<<HTML
<a class="right-text" href="{$url}">
    明细
</a>
HTML;

$js = <<<JS
$('select').select();
JS;

$this->extend('../layouts/header', compact('header_btn'))
    ->registerCssFile('@dialog-select.css')
    ->registerJsFile('@jquery.selectbox.min.js')
    ->registerJs($js);
?>
<div class="has-header">
    <div class="account-header">
        <p>余额账户(元)</p>
        <div class="money">0.00</div>
    </div>

    <div class="menu-list">
        <a href="javascript:$('#recharge').dialog().show();">
            <i class="fa fa-money-check-alt" aria-hidden="true"></i>
            充值
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="javascript:$('#withdraw').dialog().show();">
            <i class="fa fa-wallet" aria-hidden="true"></i>
            提现
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="<?=$this->url('./mobile/invoice')?>">
            <i class="fa fa-bookmark" aria-hidden="true"></i>
            发票管理
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
    </div>
</div>

<div id="recharge" class="dialog dialog-content" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">充值</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <p>充值金额</p>
        <div class="money-input">
            <em>￥</em>
            <input type="text" placeholder="0.00">
        </div>
    </div>
    <div class="dialog-footer">
        <button class="dialog-yes">下一步</button>
    </div>
</div>

<div id="withdraw" class="dialog dialog-content" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">提现</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <p>充值金额</p>
        <div class="money-input">
            <em>￥</em>
            <input type="text" placeholder="0.00">
        </div>
        <p>提现到账户</p>
        <div class="row-input">
            <select name="" id="">
                <option value="1">支付宝</option>
                <option value="2">微信</option>
                <option value="3">工商</option>
            </select>
        </div>
        <div class="row-input">
            <input type="text" placeholder="卡号/账号">
        </div>
        <div class="row-input">
            <input type="text" placeholder="姓名">
        </div>
    </div>
    <div class="dialog-footer">
        <button class="dialog-yes">提交提现申请</button>
    </div>
</div>