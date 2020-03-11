<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '书单-'.$list->title;
$this->body_class = 'bodyph';
$this->extend('layouts/header');
?>
<div class="clear"></div>
<!--body开始-->
<div class="box-container local">当前位置：
    <a href="<?=$this->url('./')?>" title=""><?=$site_name?></a>&nbsp;>&nbsp;
    <a href="<?=$this->url('./list')?>">书单</a>
</div>
<div class="clear"></div>
<div class="box-container m_list list">
    <div class="book-list-info">
        <div class="info-main">
            <div class="title"><?=$list->title?></div>
            <p class="desc"><?=$list->description?></p>
            <div class="info-footer">
                <a href=""><?=$list->user->name?></a>
                <span><?=$list->created_at?></span>
            </div>
        </div>
        <div class="info-action">
            <div class="info-count">
                <span><?=$list->book_count?>本书</span>
                <span><?=$list->click_count?>浏览</span>
                <span><?=$list->collect_count?>收藏</span>
            </div>
            <a href="" class="btn">收藏</a>
        </div>
    </div>

    <?php foreach($items as $item):?>
        <div class="list-book-item">
            <div class="thumb">
                <a href="<?=$this->url('./book', ['id' => $item->book_id])?>">
                    <img src="/assets/images/book_default.jpg" alt="">
                </a>
            </div>
            <div class="info">
                <a href="<?=$this->url('./book', ['id' => $item->book_id])?>" class="title"><?=$item->book->name?></a>
                <p>
                    <a href="">作者</a>
                    <span>1w</span>
                    <span>完结</span>
                </p>
                <p>
                更新时间：4 年前
                </p>
                <p>
                单主评分：
                    <div class="star-box">
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                    </div>
                </p>
            </div>
            <p class="remark">
                <?=$item->remark?>
            </p>
            <div class="action-box">
                <a href="">
                    赞
                </a>
                <a href="">
                    踩
                </a>
                <a href="">
                    加入书架
                </a>
            </div>
        </div>
    <?php endforeach;?>
</div>
<!--body结束-->
<div class="clear"></div>
<?php $this->extend('layouts/footer');?>