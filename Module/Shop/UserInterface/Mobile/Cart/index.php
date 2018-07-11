<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的购物车';

$js = <<<JS
$(".swipe-row").swipeAction();
JS;

$this->extend('../layouts/header')
    ->registerJsFile('@jquery.swipeAction.min.js')
    ->registerJs($js);
?>

<div class="has-header">
    <div class="cart-box">
        <div class="swipe-box goods-list">
            <?php foreach($goods_list as $item):?>
            <div class="swipe-row">
                <div class="swipe-content goods-item">
                    <i class="fa check-box"></i>
                    <div class="goods-img">
                        <img src="<?=$item->goods->thumb?>" alt="">
                    </div>
                    <div class="goods-info">
                        <h4><?=$item->goods->name?></h4>
                        <span><?=$item->price?></span>
                        <div class="number-box">
                            <i class="fa fa-minus"></i>
                            <input type="text" name="" value="<?=$item->number?>">
                            <i class="fa fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="actions-right">
                    <i class="fa fa-trash"></i>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="cart-footer">
        <i class="fa check-box"></i>
        <span>全选</span>

        <div class="cart-amount">
            <span>0</span>
            <a href="<?=$this->url('./mobile/cashier')?>" class="btn">结算</a>
        </div>
    </div>
</div>

<?php $this->extend('../layouts/navbar');?>