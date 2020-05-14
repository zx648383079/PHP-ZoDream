<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<a href="<?=$this->url('./cart')?>">
    <i class="fa fa-shopping-cart"></i>
    <i class="cart-num"><?=$cart->count()?></i>
</a>
<?php if(!$cart->isEmpty()):?>
<div class="cart-dialog">
    <div class="dialog-body">
        <?php foreach($cart as $group):?>
            <?php foreach($group as $item):?>
            <div class="cart-item" data-id="<?=$item->id?>">
                <div class="thumb">
                    <img src="<?=$item->goods->thumb?>" alt="">
                </div>
                <div>
                    <div class="name"><?=$item->goods->name?></div>
                    <span class="attr">雾白 </span>
                    <span class="count">x <?=$item->number?></span>
                </div>
                <div class="price">
                    <?=$item->total?>
                </div>
                <div class="action">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <?php endforeach;?>
        <?php endforeach;?>
    </div>
    <div class="dialog-footer">
        <div class="total">
            <span>SUBTOTAL:</span>
            <span><?=$cart->total()?></span>
        </div>
        <a href="<?=$this->url('./cart')?>" class="btn">VIEW CART</a>
    </div>
</div>
<?php endif;?>