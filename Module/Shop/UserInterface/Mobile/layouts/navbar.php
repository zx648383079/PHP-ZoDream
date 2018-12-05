<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$index = 0;
if (url()->hasUri('category')) {
    $index = 1;
} elseif (url()->hasUri('cart')) {
    $index = 2;
} elseif (url()->hasUri('member')) {
    $index = 3;
}
?>
<footer class="tab-bar">
    <a href="<?=$this->url('./mobile')?>" <?=$index < 1 ? 'class="active"' : ''?>>
        <i class="fa fa-home" aria-hidden="true"></i>
        首页
    </a>
    <a href="<?=$this->url('./mobile/category')?>" <?=$index == 1 ? 'class="active"' : ''?>>
        <i class="fa fa-th-large" aria-hidden="true"></i>
        分类
    </a>
    <a href="<?=$this->url('./mobile/cart')?>" <?=$index == 2 ? 'class="active"' : ''?>>
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        购物车
    </a>
    <a href="<?=$this->url('./mobile/member')?>" <?=$index == 3 ? 'class="active"' : ''?>>
        <i class="fa fa-user" aria-hidden="true"></i>
        我的
    </a>
</footer>