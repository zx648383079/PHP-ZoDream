<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Shop';
$js = <<<JS
var silder = $(".banner .slider").slider({
    width: 1,
    height: .53,
});
JS;
$this->registerCssFile('@slider.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js, View::JQUERY_READY)
    ->extend('../layouts/search');
?>

<div class="has-header">

    <div class="banner">
        <div class="slider">
            <div class="slider-box">
                <ul>
                    <li><img src="https://yanxuan.nosdn.127.net/08c22f34ed0445208c8bbf80cb769d06.jpg?imageView&quality=75&thumbnail=750x0" width="100%" alt=""></li>
                    <li><img src="https://yanxuan.nosdn.127.net/8271dce9c32d58eb8598c1408acf6132.jpg?imageView&quality=75&thumbnail=750x0" width="100%" alt=""></li>
                    <li><img src="https://yanxuan.nosdn.127.net/3693d1b5948a2072fdf3524668e11993.jpg?imageView&quality=75&thumbnail=750x0" width="100%" alt=""></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="menu-box">
        <?php foreach($cat_list as $item):?>
        <a href="<?=$this->url('./mobile/search', ['cat_id' => $item->id])?>" class="menu-item">
            <img class="menu-icon" src="<?=$item->icon?>" alt="">
            <div class="menu-name"><?=$item->name?></div>
        </a>
        <?php endforeach;?>
    </div>

    <div class="goods-list">
        <?php foreach($hot_list as $item):?>
        <div class="item-view">
            <div class="item-img">
                <a href="<?=$this->url('./mobile/goods', ['id' => $item->id])?>"><img src="<?=$item->thumb?>" alt=""></a>
            </div>
            <div class="item-title">
                <?=$item->name?>
            </div>
            <div class="item-actions">
                <span class="item-price"><?=$item->price?></span>
                <span>加入购物车</span>
            </div>
        </div>
        <?php endforeach;?>
    </div>

</div>

<?php $this->extend('../layouts/navbar');?>