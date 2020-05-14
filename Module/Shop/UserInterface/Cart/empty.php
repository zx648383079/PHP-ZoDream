<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="empty-cart-box">
    <?php if(auth()->guest()):?>
    <p>After login, you can synchronize the products in the shopping cart</p>
    <a href="<?=$this->url('./member/login')?>" class="btn">Sign up now</a>
    <?php else:?>
    <p>Your cart is empty</p>
    <a href="<?=$this->url('./')?>" class="btn">Go Shopping</a>
    <?php endif;?>
</div>