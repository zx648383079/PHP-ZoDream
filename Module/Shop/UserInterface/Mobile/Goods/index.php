<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<header class="top">
    <a class="back">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
    </a>
    <div class="top-tab">
        <a href="" class="active">商品</a>
        <a href="">详情</a>
        <a href="">评价</a>
        <a href="">推荐</a>
    </div>
    <!-- <a class="btn" href="login.html">
        退出
    </a> -->
</header>

<div class="has-header">

    <div class="goods-list">
        <?php foreach($goods_list as $item):?>
        <div class="item-view">
            <div class="item-img">
                <a href="<?=$this->url('./mobile/goods', ['id' => $item->id])?>"><img src="<?=$item->thumb?>" alt=""></a>
            </div>
            <div class="item-title">
                <?=$item->name?>
            </div>
            <div class="item-actions">
                <span class="item-price"><?=$item->price?></span>
                <span>加入购物车</span>
            </div>
        </div>
        <?php endforeach;?>
    </div>

</div>

<footer class="goods-navbar">
    <a href="<?=$this->url('./mobile')?>">
        <i class="fa fa-home" aria-hidden="true"></i>
        首页
    </a>
    <a href="<?=$this->url('./mobile/category')?>">
        <i class="fa fa-th-large" aria-hidden="true"></i>
        分类
    </a>
    <a href="<?=$this->url('./mobile/cart')?>">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        购物车
    </a>
    <a class="btn btn-orange" href="<?=$this->url('./mobile/user')?>">
        加入购物车
    </a>
    <a class="btn" href="<?=$this->url('./mobile/user')?>">
        立即购买
    </a>
</footer>