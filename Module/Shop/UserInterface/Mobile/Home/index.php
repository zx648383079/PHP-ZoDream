<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Shop';
$this->extend('../layouts/search');
?>

<div class="has-header">

    <div class="banner-box">

    </div>

    <div class="menu-box">
        <?php foreach($cat_list as $item):?>
        <a href="<?=$this->url('./mobile/search', ['cat_id' => $item->id])?>" class="menu-item">
            <img class="menu-icon" src="<?=$item->thumb?>" alt="">
            <div class="menu-name"><?=$item->name?></div>
        </a>
        <?php endforeach;?>
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
                <span>加入购物车</span>
            </div>
        </div>
        <?php endforeach;?>
    </div>

</div>

<?php $this->extend('../layouts/navbar');?>