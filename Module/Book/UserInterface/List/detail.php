<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '书单-'.$list['title'];
?>

<div class="container">
    <div class="panel-container novel-list-info">
        <div class="row">
            <div class="col-md-8">
                <div class="info-main">
                    <div class="title"><?=$list['title']?></div>
                    <p class="desc"><?=$list['description']?></p>
                    <div class="info-footer">
                        <a href=""><?=$list['user']['name']?></a>
                        <span class="time"><?=$list['created_at']?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-action">
                    <div class="info-count">
                        <span><?=$list['book_count']?>本书</span>
                        <span><?=$list['click_count']?>浏览</span>
                        <span><?=$list['collect_count']?>收藏</span>
                    </div>
                    <div class="text-center mt-30">
                        <div class="btn btn-primary">
                            <i class="fa fa-heart"></i>
                            收藏
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-container">
        <?php foreach($model_list as $item):?>
        <div class="list-novel-item">
            <div class="item-thumb">
                <a href="<?=$this->url('./book', ['id' => $item['book_id']])?>">
                    <img src="<?= $item['book']['cover'] ?>" alt="">
                </a>
            </div>
            <div class="item-body">
                <a class="item-title" href="<?=$this->url('./book', ['id' => $item['book_id']])?>"><?=$item['book']['name']?></a>
                <p>
                    <a><?=$item['book']['author']['name']?></a>|
                    <span><?=$item['book']['format_size']?></span>|
                    <span><?=$item['book']['status_label']?></span>
                </p>
                <p>
                    更新时间：<?=$item['updated_at']?>
                </p>
                <div>
                    单主评分：
                    <div class="star-bar">
                    <?php for($i = 0; $i < 10; $i += 2):?>
                        <i class="fa fa-star <?=$i < $item['star'] ? 'light' : ''?>"></i>
                    <?php endfor;?>
                    </div>
                </div>
            </div>
            <p class="item-remark">
                <?=$item['remark']?>
            </p>
            <div class="item-action">
                <a>
                    <i class="fa fa-thumbs-up"></i><?= $item['agree_count'] ?>
                </a>
                <a>
                    <i class="fa fa-thumbs-down"></i><?= $item['disagree_count'] ?>
                </a>
                <a>
                    加入书架
                </a>
            </div>
        </div>
        <?php endforeach;?>
    </div>

</div>
