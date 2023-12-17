<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$js = <<<JS
$(".slider-goods").slider({
    haspoint: false
});
JS;
$this->registerCssFile('@slider.min.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="cart-page">
    <div class="container">

        <?php if($cart->isEmpty()):?>
            <?php $this->extend('./empty');?>
        <?php else:?>
            <?php $this->extend('./list');?>
        <?php endif;?>


        <div class="panel">
            <div class="panel-header">
            You like
            </div>
            <div class="panel-body">
                <div class="slider slider-goods" data-height="279" data-width="210">
                    <div class="slider-previous">&lt;</div>
                    <div class="slider-box">
                        <ul>
                            <?php foreach($like_goods as $item):?>
                            <li class="goods-item">
                                <div class="thumb">
                                    <img src="<?=$item->thumb?>" alt="">
                                </div>
                                <div class="name"><?=$item->name?></div>
                                <div class="price"><?=$item->price?></div>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <div class="slider-next">&gt;</div>
                </div>
            </div>
        </div>

    </div>
</div>