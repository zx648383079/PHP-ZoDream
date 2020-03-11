<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '书单';
$this->body_class = 'bodyph';
$this->extend('layouts/header');
?>
<div class="clear"></div>
<!--body开始-->
<div class="box-container local">当前位置：
    <a href="<?=$this->url('./')?>" title=""><?=$site_name?></a>&nbsp;>&nbsp;
    <a href="<?=$this->url('./list')?>">书单</a></div>
<div class="clear"></div>
<div class="box-container m_list list">
    <?php foreach($model_list as $item):?>
        <div class="book-list-item">
            <div class="thumb">
                <a href="<?=$this->url('./list', ['id' => $item->id])?>">
                    <img src="/assets/images/book_default.jpg" alt="">
                    <img src="/assets/images/book_default.jpg" alt="">
                    <img src="/assets/images/book_default.jpg" alt="">
                </a>
            </div>
            <div class="info">
                <a href="<?=$this->url('./list', ['id' => $item->id])?>" class="title"><?=$item->title?></a>
                <p class="desc"><?=$item->description?></p>
                <div class="info-footer">
                    <a href=""><?=$item->user->name?></a>
                    <span><?=$item->created_at?></span>

                    <div class="info-count">
                        <span><?=$item->book_count?>本书</span>
                        <span><?=$item->click_count?>浏览</span>
                        <span><?=$item->collect_count?>收藏</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>
<!--body结束-->
<div class="clear"></div>
<?php $this->extend('layouts/footer');?>