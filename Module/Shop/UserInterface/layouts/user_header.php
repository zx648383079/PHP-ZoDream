<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<header class="user-header">
    <div class="header-top">
        <div class="container">
            <div class="top-left">
                <a class="home" href="<?=$this->url('./')?>">
                    <i class="fa fa-home"></i>
                    首页
                </a>
                <!-- <a href="">
                    好的生活，没那么贵
                </a> -->
                <div class="top-notice">
                    <i class="fa fa-volume-up"></i>
                    <ul class="notice-list">
                        <li>
                            <a href="">双十一期间严选客服服务时间通知</a>
                        </li>
                        <li>
                            <a href="">双十一期间严选发货时效说明</a>
                        </li>
                    </ul>
                </div>
                
            </div>
            <div class="top-right">
                <div class="top-item">
                    <a href="<?=$this->url('./member')?>"><?=auth()->user()->name?></a>
                    <a href="<?=$this->url('/auth/logout', ['redirect_uri' => $this->url('./')])?>">退出</a>
                </div>
                <div class="top-item">
                    我的订单
                </div>
                <div class="top-item">
                    会员
                </div>
                <div class="top-item">
                    甄选家
                </div>
                <div class="top-item">
                    企业采购
                </div>
                <div class="top-item">
                    客户服务
                </div>
                <div class="top-item">
                    <i class="fa fa-mobile"></i>
                    APP
                </div>
            </div>
        </div>
    </div>
    <div class="header-main">
        <div class="container">
            <div class="header-logo">
                <img src="http://yanxuan.nosdn.127.net/3db3a7a0bae656df51581fa14f4061d9.gif" alt="">
            </div>
            <div class="header-title">
                个人中心
            </div>
        </div>
    </div>
</header>