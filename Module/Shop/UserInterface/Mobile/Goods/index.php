<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '商品详情';
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

<div class="has-header has-footer">

    <div class="goods-gallary-box">
        <img src="<?=$goods->thumb?>" alt="">
    </div>

    <div class="goods-info">
        <div class="goods-header">
            <h1 class="goods-name"><?=$goods->name?></h1>
            <div class="goods-collect <?=$goods->is_collect ? 'active' : ''?>" onclick="collectGoods('<?=$goods->id?>', this)">
                <i class="like-icon"></i>
                收藏
            </div>
        </div>
        <div class="goods-price"><?=$goods->price?></div>
    </div>

    <div class="comment-box">
        <div class="comment-header">
            评价
            <i class="fa fa-angle-right"></i>
        </div>
        <div class="comment-stats">
            <a href="">好评（2000）</a>
        </div>
        <?php foreach($comment_list as $item):?>
        <div class="comment-item">
            <div class="item-header">
                <div class="avatar">
                    <img src="<?=$item->user->avatar?>" alt="">
                </div>
                <div class="name"><?=$item->user->name?></div>
                <div class="score">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
            </div>
            <div class="time">
                <span><?=$item->created_at?> </span>
                <div class="attr">
                规格:雾白
                </div>
            </div>
            <div class="content"><?=$item->content?></div>
            <ul class="image-box">
                <?php foreach($item->images as $img):?>
                <li>
                    <img src="<?=$img->image?>" alt="">
                </li>
                <?php endforeach;?>
            </ul>
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
    <a class="btn btn-orange" href="javascript:addToCart('<?=$goods->id?>');">
        加入购物车
    </a>
    <a class="btn" href="<?=$this->url('./mobile/user')?>">
        立即购买
    </a>
</footer>

<div class="cart-dialog">
    <div class="dialog-body">
        <div class="dialog-header">
            <img src="<?=$goods->thumb?>" alt="">
            <p class="price"><?=$goods->price?></p>
            <p class="selected-property">111</p>
            <i class="fa fa-times dialog-close"></i>
        </div>
        <div class="property-box">
            <div class="group">
                <div class="group-header">类型</div>
                <div class="group-body">
                    <span class="active">11111</span>
                    <span>11111</span>
                    <span>11111</span>
                    <span>11111</span>
                </div>
            </div>

            <div class="count-box">
                <span>数量</span>
                <div class="number-box">
                    <i class="fa fa-minus"></i>
                    <input type="text" class="number-input" value="1">
                    <i class="fa fa-plus"></i>
                </div>
            </div>
        </div>
        <div class="dialog-footer">
            <a href="" class="dailog-yes">确认</a>
        </div>
    </div>
</div>