<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Account Admin';
?>

<div class="row">
    <div class="col-md-4">
        <div class="column-full-item">
            <div class="inner">
                <h3><?= intval($data['user_today']) ?>/<?= intval($data['user_yesterday']) ?>/<?= intval($data['user_count']) ?></h3>
                <p>今日/昨日/总用户</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <a class="column-footer" routerLink="users">
                查看更多
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="column-full-item">
            <div class="inner">
                <h3><?= intval($data['login_today']) ?>/<?= intval($data['login_yesterday']) ?></h3>
                <p>今日/昨日活跃用户</p>
            </div>
            <div class="icon">
                <i class="fa fa-sign-out-alt"></i>
            </div>
            <a class="column-footer" routerLink="users">
                查看更多
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>