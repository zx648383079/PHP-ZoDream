<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '评价管理';

$this->extend('../layouts/header');
?>
<div class="has-header">
    <div class="tab-bar order-header">
        <a href="" class="active">待评价</a>
        <a href="">已评价</a>
    </div>

    <div class="order-box">
    <?php foreach($goods_list as $goods):?>
        <div class="goods-item">
            <div class="goods-img">
                <img src="<?=$goods->thumb?>" alt="">
            </div>
            <div class="goods-info">
                <h4><?=$goods->name?></h4>
                <a href="">评价</a>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>