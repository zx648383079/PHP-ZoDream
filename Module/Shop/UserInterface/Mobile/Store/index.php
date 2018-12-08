<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '店铺';
$js = <<<JS
bindStore();
JS;
$this->registerJs($js);
?>
<div class="store-page">
    <header class="store-header">
        <div class="search-back-box">
            <a class="back" href="javascript:history.back(-1);">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </a>
            <a class="search-entry" href="<?=$this->url('./mobile/search')?>">
                <i class="fa fa-search" aria-hidden="true"></i>
                <span>搜索本店商品</span>
            </a>
        </div>
        <div class="store-info">
            <div class="logo">
                <img src="/assets/images/avatar/1.png" alt="">
            </div>
            <div class="info">
                <div class="name">12345545</div>
                <p>233万人收藏</p>
            </div>
            <div class="action">
                <a href="">
                    <i class="fa fa-star"></i>    
                    收藏
                </a>
            </div>
        </div>
        <div class="tab-bar">
            <a href="" class="active">首页</a>
            <a href="">全部商品</a>
            <a href="">促销</a>
            <a href="">动态</a>
        </div>
    </header>

    <div class="store-body">

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
</div>
