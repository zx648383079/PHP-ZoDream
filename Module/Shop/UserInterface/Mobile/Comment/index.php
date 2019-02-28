<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '评价管理';
$status_list = [
    0 => '待评价',
    1 => '已评价',
];
$this->extend('../layouts/header');
?>
<div class="has-header">
    <div class="tab-bar order-header">
        <?php foreach($status_list as $key => $item):?>
        <a href="<?=$this->url('./mobile/comment', ['status' => $key])?>" <?=$status == $key ? 'class="active"': ''?>><?=$item?></a>
        <?php endforeach;?>
    </div>

    <div class="comment-list-box">
        <div class="goods-list">
            <?php foreach($goods_list as $goods):?>
            <div class="goods-item">
                <div class="goods-img">
                    <img src="<?=$goods->thumb?>" alt="">
                </div>
                <div class="goods-info">
                    <h4><?=$goods->name?></h4>
                    <?php if($goods->comment_id < 1):?>
                    <a href="<?=$this->url('./mobile/comment/create', ['goods' => $goods->id])?>" class="comment-btn">评价</a>
                    <?php endif;?>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>