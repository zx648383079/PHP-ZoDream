<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '帐号安全';
?>
<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div class="message-box">
            <div class="message-menu">
                <a href="" class="menu-item">
                    <div class="icon">
                        <i class="fa fa-mobile"></i>
                    </div>
                    <div class="info">
                        <div class="name">系统通知</div>
                        <p>无未读消息</p>
                    </div>
                </a>
                <a href="" class="menu-item active">
                    <div class="icon">
                        <i class="fa fa-mobile"></i>
                    </div>
                    <div class="info">
                        <div class="name">系统通知</div>
                        <p>无未读消息</p>
                    </div>
                </a>
                <a href="" class="menu-item">
                    <div class="icon">
                        <i class="fa fa-mobile"></i>
                    </div>
                    <div class="info">
                        <div class="name">系统通知</div>
                        <p>无未读消息</p>
                    </div>
                </a>
            </div>
            <div class="message-body">

            </div>
        </div>


    </div>
</div>
