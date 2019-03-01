<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '商品评价';
$goods_url = $this->url('./mobile/goods', ['id' => $id]);
?>
<header class="top">
    <a href="javascript:history.back(-1);" class="back">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
    </a>
    <div class="top-tab">
        <a href="<?=$goods_url?>#info">商品</a>
        <a href="<?=$goods_url?>#detail">详情</a>
        <a href="#comments" class="active">评价</a>
        <a href="<?=$goods_url?>#recommend">推荐</a>
    </div>
</header>

<div class="has-header">

    <div id="comments" class="comment-box">
        <div class="comment-subtotal">
            评分
            <div class="score">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <span>100%</span>好评
        </div>
        <div class="comment-stats">
            <a href="">好评（2000）</a>
        </div>
        <?php $this->extend('./page');?>
    </div>

    <?php if($comment_list->hasMore()):?>
    <div class="more-load" data-page="<?=$comment_list->getIndex()?>" data-target="#comments" data-url="<?=$this->url(['page' => null])?>">
    加载中。。。
    </div>
    <?php endif;?>
</div>
