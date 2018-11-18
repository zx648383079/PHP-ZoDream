<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<header>
    <div class="header-top">
        <div class="container">
            <div class="top-left">
                <a href="">
                    好的生活，没那么贵
                </a>
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
                    <a href="<?=$this->url('./member/login')?>">登录</a>
                    <a href="<?=$this->url('./member/login')?>">注册</a>
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
            
            <ul class="header-nav">
                <li>
                    <a href="<?=$this->url('./')?>">首页</a>
                </li>
                <?php foreach($categories_tree as $item):?>
                <li>
                    <a href="<?=$this->url('./category', ['id' => $item['id']])?>"><?=$item['name']?></a>
                    <div class="nav-dropdown">
                        <?php if(isset($item['children'])):foreach($item['children'] as $column):?>
                        <div class="nav-column">
                            <div class="nav-title"><?=$column['name']?></div>
                            <?php if(isset($column['children'])):foreach($column['children'] as $child):?>
                            <div class="nav-item">
                                <img src="https://yanxuan.nosdn.127.net/785a1507ce654746875063805c6c4235.png" alt="">
                                <span><?=$child['name']?></span>
                            </div>
                            <?php endforeach;endif;?>
                        </div>
                        <?php endforeach;endif;?>
                    </div>
                </li>
                <?php endforeach;?>
            </ul>
            <div class="header-search" data-url="<?=$this->url('./search')?>">
                <input type="text" name="keywords" placeholder="  搜索">
                <i class="fa fa-search"></i>
            </div>
            <div class="header-cart">
                <i class="fa fa-shopping-cart"></i>
                <i class="cart-num">0</i>

                <div class="cart-dialog">
                    <div class="dialog-body">
                        <?php foreach($cart as $item):?>
                        <div class="cart-item">
                            <div class="thumb">
                                <img src="<?=$item->goods->thumb?>" alt="">
                            </div>
                            <div>
                                <div class="name"><?=$item->goods->name?></div>
                                <span class="attr">雾白 </span>
                                <span class="count">x <?=$item->number?></span>
                            </div>
                            <div class="price">
                                <?=$item->total?>
                            </div>
                            <div>
                                <i class="fa fa-remove"></i>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <div class="dialog-footer">
                        <div class="total">
                            <span>商品合计:</span>
                            <span>￥0.00</span>
                        </div>
                        <a href="<?=$this->url('./cart')?>" class="btn">去购物车结算</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>