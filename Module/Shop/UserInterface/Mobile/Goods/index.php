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

    <div class="goods-gallary-box">
        <img src="<?=$goods->thumb?>" alt="">
    </div>

    <div class="goods-info">
        <div class="goods-header">
            <h1 class="goods-name"><?=$goods->name?></h1>
            <div class="goods-collect">
                <i class="fa fa-like"></i>
                收藏
            </div>
        </div>
        <div class="goods-price"><?=$goods->price?></div>
    </div>

    <div class="comment-box">
        <div class="comment-header">
            评价
            <i class="fa fa-next"></i>
        </div>
        <div class="comment-stats">
            <a href="">好评（2000）</a>
        </div>
        <?php foreach($comment_list as $item):?>
        <div class="comment-item">
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
        <a href="" class="comment-more">查看更多</a>
    </div>

    <div class="recomment-box">
        <div class="recommend-header">推荐</div>
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
        <div class="clearfix"></div>
    </div>

    <div class="tab-box goods-content">
        <div class="tab-header"><div class="tab-item active">商品介绍</div><div class="tab-item">规格参数</div><div class="tab-item">售后保障</div></div>
        <div class="tab-body">
            <div class="tab-item active"><?=$item->content?></div>
            <div class="tab-item">规格参数</div>
            <div class="tab-item">售后保障</div>
        </div>
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