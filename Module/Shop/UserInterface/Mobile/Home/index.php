<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Shop';
$js = <<<JS
var silder = $(".banner .slider").slider({
    width: 1,
    height: .53,
});
JS;
$this->registerCssFile('@slider.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

<header class="top top-search-box">
    <a href="<?=$this->url('./mobile')?>" class="logo">
        <img src="/assets/images/wap_logo.png" alt="">
    </a>
    <a class="search-entry" href="<?=$this->url('./mobile/search')?>">
        <i class="fa fa-search"></i>
        <span>搜索商品, 共<?=$site['goods']?>款好物</span>
    </a>
    <?php if(auth()->guest()):?>
        <a href="<?=$this->url('./mobile/member/login')?>">登录</a>
    <?php else:?>
        <a href="<?=$this->url('./mobile/message')?>">
            <i class="fa fa-comment-dots"></i>
        </a>
    <?php endif;?>
</header>

<div class="has-header has-footer">

    <div class="banner">
        <div class="slider">
            <div class="slider-box">
                <ul>
                    <?php foreach($banners as $item):?>
                    <li><img src="<?=$item['content']?>" width="100%" alt=""></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>

    <div class="menu-box">
        <?php foreach($cat_list as $item):?>
        <a href="<?=$this->url('./mobile/search', ['cat_id' => $item->id])?>" class="menu-item">
            <img class="menu-icon" src="<?=$item->icon?>" alt="">
            <div class="menu-name"><?=$item->name?></div>
        </a>
        <?php endforeach;?>
    </div>

    <?php if(!empty($new_list)):?>
    <div class="home-panel">
        <div class="panel-header">最新商品</div>
        <div class="goods-list">
            <?php foreach($new_list as $item):?>
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
    <?php endif;?>

    <?php if(!empty($hot_list)):?>
    <div class="home-panel">
        <div class="panel-header">热门商品</div>
        <div class="goods-list">
            <?php foreach($hot_list as $item):?>
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
    <?php endif;?>

    <?php if(!empty($best_list)):?>
    <div class="home-panel">
        <div class="panel-header">推荐商品</div>
        <div class="goods-list">
            <?php foreach($best_list as $item):?>
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
    <?php endif;?>

</div>

<?php $this->extend('../layouts/navbar');?>