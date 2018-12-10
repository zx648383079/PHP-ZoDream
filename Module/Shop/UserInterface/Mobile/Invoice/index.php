<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '发票管理';

$this->extend('../layouts/header');
?>
<div class="has-header">
    <div class="account-header">
        <p>可开发票总金额(元)</p>
        <div class="money">0.00</div>
    </div>

    <div class="menu-list">
        <a href="<?=$this->url('./mobile/invoice/apply')?>">
            <i class="fa fa-money-check-alt" aria-hidden="true"></i>
            申请开票
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="<?=$this->url('./mobile/invoice/title')?>">
            <i class="fa fa-wallet" aria-hidden="true"></i>
            发票抬头
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <a href="<?=$this->url('./mobile/invoice/log')?>">
            <i class="fa fa-bookmark" aria-hidden="true"></i>
            近期开票
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
    </div>
</div>
