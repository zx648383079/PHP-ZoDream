<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '推荐的订单';
$this->extend('../layouts/header');
?>
<div class="has-header">
<div class="log-hr">
        2018年12月
    </div>
    <?php foreach(range(1, 20) as $item):?>
    <div class="log-item">
        <div class="info">
            <div class="name">12312332312</div>
            <p><?=date('m月d日 H:i')?></p>
        </div>
        <div class="amount">
            +10
        </div>
    </div>
    <?php endforeach;?>
</div>