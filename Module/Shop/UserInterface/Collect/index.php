<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的收藏';
?>
<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div class="panel history-panel">
            <div class="panel-header">
                <span>我的收藏</span>
                <a href="" class="pull-right">
                    <i class="fa fa-trash"></i>
                    批量删除
                </a>
            </div>
            <div class="panel-body">
                <div class="goods-list">
                    <div class="goods-item goods-action item-hover">
                        <i class="fa fa-times"></i>
                        <div class="thumb">
                            <a href="">
                                <img src="https://yanxuan-item.nosdn.127.net/7a6b0736e8df99476488fd6d24d2065a.png?imageView&quality=95&thumbnail=200x200" alt="">
                            </a>
                        </div>
                        <div class="name">123123</div>
                        <div class="price">88</div>
                        <div class="action">
                            <a href="" class="btn">找相似</a>
                            <a href="" class="btn">加入购物车</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
