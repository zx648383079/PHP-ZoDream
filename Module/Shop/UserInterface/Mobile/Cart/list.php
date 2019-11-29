<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$js = <<<JS
$(".swipe-row").swipeAction();
bindCart();
JS;

$this->registerJsFile('@jquery.swipeAction.min.js')
    ->registerJs($js);
?>
<div class="cart-box">
    <?php foreach($cart as $group):?>
    <div class="cart-group-item">
        <div class="group-header">
            <i class="fa check-box"></i>
            <span><?=$group->getName()?></span>
        </div>
        <div class="swipe-box goods-list">
            <?php foreach($group as $item):?>
            <div class="swipe-row cart-item" data-id="<?=$item->id?>">
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
                            <input type="text" name="" value="<?=$item->amount?>" min="1">
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
    <?php endforeach;?>
</div>
<div class="cart-footer">
    <i class="fa check-box"></i>
    <span>全选</span>

    <div class="cart-amount">
        <span><?=$cart->total()?></span>
        <a href="<?=$this->url('./mobile/cashier')?>" class="btn">结算</a>
    </div>
</div>