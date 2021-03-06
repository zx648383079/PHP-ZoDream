<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<header>
    <div class="header-top">
        <div class="container">
            <div class="top-left">
                <a href="<?=$this->url('./')?>">
                    Easy Life
                </a>
                <div class="top-notice">
                    <i class="fa fa-volume-up"></i>
                    <ul class="notice-list">
                        <?php foreach($notice_list as $item):?>
                        <li>
                            <a href="<?=$this->url('./article', ['id' => $item['id']])?>"><?=$item['title']?></a>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                
            </div>
            <div class="top-right">
                <div class="top-item">
                    <?php if(auth()->guest()):?>
                    <a href="<?=$this->url('./member/login')?>">Sign in</a>
                    <a href="<?=$this->url('./member/login')?>">Sign up</a>
                    <?php else:?>
                    <a href="<?=$this->url('./member')?>"><?=auth()->user()->name?></a>
                    <a href="<?=$this->url('/auth/logout', ['redirect_uri' => $this->url('./')])?>">Sign out</a>
                    <?php endif;?>
                </div>
                <div class="top-item">
                    <a href="<?=$this->url('./order')?>">Orders</a>
                </div>
                <div class="top-item">
                    Account
                </div>
                <!-- <div class="top-item">
                    甄选家
                </div>
                <div class="top-item">
                    企业采购
                </div> -->
                <div class="top-item">
                    Customer Service
                </div>
                <div class="top-item">
                    <i class="fa fa-mobile"></i>
                    App
                </div>
            </div>
        </div>
    </div>
    <div class="header-main">
        <div class="container">
            <div class="header-logo">
                <img src="<?=$this->asset('images/wap_logo.png')?>" alt="">
            </div>
            
            <ul class="header-nav">
                <li>
                    <a href="<?=$this->url('./')?>">Home</a>
                </li>
                <?php foreach($categories_tree as $item):?>
                <li>
                    <a href="<?=$this->url('./category', ['id' => $item['id']])?>"><?=$item['name']?></a>
                    <div class="nav-dropdown">
                        <?php if(isset($item['children'])):foreach($item['children'] as $column):?>
                        <div class="nav-column">
                            <div class="nav-title">
                                <a href="<?=$this->url('./category', ['id' => $column['id']])?>"><?=$column['name']?></a>
                            </div>
                            <?php if(isset($column['children'])):foreach($column['children'] as $child):?>
                            <div class="nav-item">
                                <img src="https://yanxuan.nosdn.127.net/785a1507ce654746875063805c6c4235.png" alt="">
                                <span><a href="<?=$this->url('./category', ['id' => $child['id']])?>"><?=$child['name']?></a></span>
                            </div>
                            <?php endforeach;endif;?>
                        </div>
                        <?php endforeach;endif;?>
                    </div>
                </li>
                <?php endforeach;?>
            </ul>
            <div class="header-search" data-url="<?=$this->url('./search')?>">
                <input type="text" name="keywords" placeholder="Search ...">
                <i class="fa fa-search"></i>
            </div>
            <div class="header-cart" data-url="<?=$this->url('./cart/mini')?>">
                <?php $this->extend('Cart/mini');?>
            </div>
        </div>
    </div>
</header>