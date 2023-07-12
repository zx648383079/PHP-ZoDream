<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '书单';
?>

<div class="container main-box mt-30">
    <?php foreach($model_list as $item):?>
    <div class="novel-list-item">
        <div class="item-thumb">
            <?php foreach($item['items'] as $it):?>
            <img src="<?= $it['book']['cover'] ?>" alt="">
            <?php endforeach;?>
        </div>
        <div class="item-body">
            <a class="item-title" href="<?=$this->url('./list', ['id' => $item['id']])?>"><?= $item['title'] ?></a>
            <p class="item-meta"><?= $item['description'] ?></p>
            <div class="item-footer">
                <a >
                    <i class="fa fa-user"></i>
                    <?= $item['user']['name'] ?></a>
                <span class="item-time">
                    <i class="fa fa-clock"></i>
                    <?= $item['created_at'] ?></span>

                <div class="item-count">
                    <span><?= $item['book_count'] ?>本书</span>
                    <span><?= $item['click_count'] ?>浏览</span>
                    <span><?= $item['collect_count'] ?>收藏</span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <?= $model_list->pageLink() ?>
</div>