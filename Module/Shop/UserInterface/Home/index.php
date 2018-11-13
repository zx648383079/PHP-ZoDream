<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Shop';
$js = <<<JS
var silder = $(".banner .slider").slider({
    width: 1,
    height: 420
});
$(".slider-goods").slider({
    width: 266,
    height: 344,
    hasPoint: false
});
JS;
$this->registerCssFile('@slider.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="banner">
    <div class="slider" data-height="420" data-animationMode="fade">
        <div class="slider-previous">&lt;</div>
       <div class="slider-box">
           <ul>
               <li><img src="https://yanxuan.nosdn.127.net/02a73327b066531cbb9057c242a90d2e.jpg?imageView&quality=95&thumbnail=1920x420" width="100%" alt=""></li>
               <li><img src="https://yanxuan.nosdn.127.net/c73499b06bc31dd4efaee7fb45bbb904.jpg?watermark&type=1&gravity=northwest&dx=0&dy=0&image=OGY0ZTRiMTA1ODMzMjFhYTYyYmQwMjEwNGI2ZmE0NzAucG5n|imageView&quality=95&thumbnail=1920x420" width="100%" alt=""></li>
               <li><img src="https://yanxuan.nosdn.127.net/384586f370cc9fdf286b40e02d57f298.jpg?watermark&type=1&gravity=northwest&dx=0&dy=0&image=OGY0ZTRiMTA1ODMzMjFhYTYyYmQwMjEwNGI2ZmE0NzAucG5n|imageView&quality=95&thumbnail=1920x420" width="100%" alt=""></li>
           </ul>
       </div>
       <div class="slider-next">&gt;</div>
   </div>
</div>
<div class="floor">
    <div class="container">
        <div class="floor-header">
            <h3>品牌制造商</h3>
            <small>工厂直达消费者，剔除品牌溢价</small>
            <a href="" class="header-right">更多制造商 &gt;</a>
        </div>
        <div class="floor-grid">
            <a href="">
                <div class="name">海外制造商</div>
                <div class="price">9.9</div>
                <img src="http://yanxuan.nosdn.127.net/802ff06dd3ef161db046eeb8db6cb4be.jpg" alt="">
            </a>
            <a href="">
                <div class="name">海外制造商</div>
                <div class="price">9.9</div>
                <img src="http://yanxuan.nosdn.127.net/c1e97be1b9730360c9c228b6a6448bca.png" alt="">
            </a>
            <a href="">
                <div class="name">海外制造商</div>
                <div class="price">9.9</div>
                <img src="http://yanxuan.nosdn.127.net/957c8d117473d103b52ff694f372a346.png" alt="">
            </a>
            <a href="">
                <div class="name">海外制造商</div>
                <div class="price">9.9</div>
                <img src="http://yanxuan.nosdn.127.net/574cea67f235598950acdbae4b5bdd5b.jpg" alt="">
            </a>
        </div>
    </div>
</div>
<div class="floor">
    <div class="container">
        <div class="floor-header">
            <h3>新品首发</h3>
            <small>为你寻觅世间好物</small>
            <a href="" class="header-right">更多新品 &gt;</a>
        </div>
        <div class="slider slider-goods" data-height="344">
            <div class="slider-previous">&lt;</div>
            <div class="slider-box">
                <ul>
                    <?php foreach($new_goods as $item):?>
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

<div class="floor floor-out">
    <div class="container">
        <div class="floor-header">
            <h3>人气推荐</h3>
            <small>编辑推荐</small>
            <small>热销总榜</small>
            <a href="" class="header-right">更多推荐 &gt;</a>
        </div>
        <div class="first-goods-list">
            <?php foreach($best_goods as $item):?>
            <a href="" class="goods-item">
                <div class="thumb">
                    <img src="<?=$item->thumb?>" alt="">
                </div>
                <div class="name"><?=$item->name?></div>
                <div class="price"><?=$item->price?></div>
            </a>
            <?php endforeach;?>
        </div>
    </div>
</div>





<!-- 弹出 app 下载图 -->
<!-- <div class="app-down-guide">
    <div class="content" style="background: url(//yanxuan.nosdn.127.net/e074a1c3c359701e236e712516189125.png);">
        <i class="fa fa-close"></i>
    </div>
</div> -->