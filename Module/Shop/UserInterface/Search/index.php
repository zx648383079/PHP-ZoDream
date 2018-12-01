<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="category-page">
    <div class="container">

        <ul class="path">
            <li>
                <a href="<?=$this->url('./')?>">全部商品</a>
            </li>
            <li>
                <?=$keywords?>
            </li>
        </ul>

        <div class="goods-area">
            <div class="filter-box">
                <div>
                    分类
                </div>
                <div>
                    <a href="" class="active">全部</a>
                    <a href="">全部</a>
                    <a href="">全部</a>
                    <a href="">全部</a>
                </div>
                <div>
                    排序
                </div>
                <div>
                    <a href="" class="active">默认</a>
                    <a href="">价格</a>
                    <a href="">全部</a>
                    <a href="">全部</a>
                </div>
            </div>
            <div class="goods-list">
                <?php foreach($goods_list as $goods):?>
                <a href="<?=$this->url('./goods', ['id' => $goods->id])?>" class="goods-item">
                    <div class="thumb">
                        <img src="<?=$goods->thumb?>" alt="">
                    </div>
                    <div class="name"><?=$goods->name?></div>
                    <div class="price"><?=$goods->price?></div>
                    <div class="desc">
                    入门级奢品，真丝细润亲肤
                    </div>
                </a>
                <?php endforeach;?>
            </div>
            <?=$goods_list->getLink()?>
        </div>

    </div>
</div>