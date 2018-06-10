<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('../layouts/header');
?>

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

<?php $this->extend('../layouts/navbar');?>