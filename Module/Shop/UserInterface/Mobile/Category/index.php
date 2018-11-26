<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('../layouts/search');
?>

<div class="has-header has-footer category-page">
    <div class="category-menu">
        <?php foreach($cat_list as $item):?>
        <div class="menu-item"><?=$item->name?></div>
        <?php endforeach;?>
    </div>

    <div>
        <div class="banner">
            <img src="http://yanxuan.nosdn.127.net/03c4a6e7790232fbf637f91fae361c7e.jpg?imageView&thumbnail=0x196&quality=75" alt="">
        </div>
        <div class="header">
            分类
        </div>
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
                 
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>

</div>

<?php $this->extend('../layouts/navbar');?>