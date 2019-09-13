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
        <div class="account-box">
            <div class="account-tip">
                <a href="">安全中心</a>
                保障账号资产安全
            </div>
            <div class="account-header">
                <div>
                    <p>我的可用余额</p>
                    <div class="money">0.00</div>
                </div>
                <div>
                    <p>
                        <i class="fa fa-mobile"></i>
                        全部余额：
                        <em>0</em>
                    </p>
                    <p>
                        <i class="fa fa-mobile"></i>
                        锁定余额：
                        <em>0</em>
                    </p>
                    <p>
                        <i class="fa fa-mobile"></i>
                        账户状态：
                        <em>有效</em>
                    </p>
                </div>
                <div>
                    <a href="" class="btn">充值</a>
                </div>
            </div>
            <div class="order-search">
               <div>
                    <div class="order-tab">
                            <a href="" class="active">收支明细</a>
                            <a href="">充值记录</a>
                    </div>
               </div>
               <div class="search-box">
                   <input type="text">
                   <button>搜索</button>
               </div>
           </div>
        </div>


    </div>
</div>
