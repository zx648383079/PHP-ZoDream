<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

?>
<div class="empty-cart-box">
    <?php if(auth()->guest()):?>
    <p>登录后可同步购物车中商品</p>
    <a href="<?=$this->url('./mobile/member/login')?>" class="btn">登录</a>
    <?php else:?>
    <p>购物车时空的</p>
    <a href="<?=$this->url('./mobile')?>" class="btn">去逛逛</a>
    <?php endif;?>
</div>