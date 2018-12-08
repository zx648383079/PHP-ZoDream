<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '开票历史';
$this->extend('../layouts/header')
?>
<div class="has-header">
    <div class="log-hr">
        2018年12月
    </div>
    <?php foreach(range(1, 20) as $item):?>
    <div class="log-item">
        <div class="info">
            <div class="name">个人</div>
            <p><?=date('Y-m-d H:i:s')?></p>
        </div>
        <div class="amount">
            ￥200
        </div>
    </div>
    <?php endforeach;?>
</div>