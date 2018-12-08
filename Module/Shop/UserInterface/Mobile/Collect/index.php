<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的收藏';
$js = <<<JS
$(".swipe-row").swipeAction();
JS;

$this->extend('../layouts/header')
    ->registerJsFile('@jquery.swipeAction.min.js')
    ->registerJs($js);
?>

<div class="has-header collect-page">
    <div class="swipe-box goods-list">
        <?php foreach($goods_list as $item):?>
        <div class="swipe-row">
            <div class="swipe-content goods-item">
                <div class="goods-img">
                    <img src="<?=$item->goods->thumb?>" alt="">
                </div>
                <div class="goods-info">
                    <h4><?=$item->goods->name?></h4>
                    <span><?=$item->goods->price?></span>
                </div>
            </div>
            <div class="actions-right">
                <i class="fa fa-trash"></i>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>