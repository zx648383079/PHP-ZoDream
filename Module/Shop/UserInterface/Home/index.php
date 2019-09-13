<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Shop';
$js = <<<JS
var silder = $(".banner .slider").slider({
    width: 1,
    height: 420,
});
$(".slider-goods").slider({
    width: 266,
    height: 344,
    haspoint: false
});
JS;
$this->registerCssFile('@slider.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="banner">
    <div class="slider" data-height="420" data-auto="true">
        <div class="slider-previous">&lt;</div>
       <div class="slider-box">
           <ul>
                <?php foreach($banners as $item):?>
                <li><img src="<?=$item['content']?>" width="100%" alt=""></li>
                <?php endforeach;?>
              
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
                    <li class="goods-item item-hover">
                        <div class="thumb">
                            <a href="<?=$this->url('./goods', ['id' => $item->id])?>">
                                <img src="<?=$item->thumb?>" alt="">
                            </a>
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

 <?php foreach($floor_categories as $item):?>
<div class="floor category-floor">
    <div class="container">
        <div class="floor-header">
            <h3><?=$item['name']?></h3>

            <div class="header-right">
                <?php foreach($item['children'] as $i => $column):?>
                <?php if($i > 0):?>
                <b class="spilt">/</b>
                <?php endif;?>
                <a href="<?=$this->url('./category', ['id' => $column['id']])?>"><?=$column['name']?></a>
                <?php endforeach;?>
                <a href="<?=$this->url('./category', ['id' => $item['id']])?>">查看更多 &gt;</a>
            </div>
            
        </div>
        <div class="category-banner">
            <img src="<?=$item['banner']?>" alt="">
        </div>
        <div class="goods-list">
            <?php foreach($item['goods'] as $goods):?>
            <a href="<?=$this->url('./goods', ['id' => $goods['id']])?>" class="goods-item item-hover">
                <div class="thumb">
                    <img src="<?=$goods->thumb?>" alt="">
                </div>
                <div class="name"><?=$goods->name?></div>
                <div class="price"><?=$goods->price?></div>
            </a>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php endforeach;?>

<div class="floor floor-out comment-floor">
    <div class="container">
        <div class="floor-header">
            <h3>大家都在说</h3>
            <small>生活，没有统一标准</small>
        </div>
        <div class="slider slider-goods" data-height="392" data-width="367">
            <div class="slider-previous">&lt;</div>
            <div class="slider-box">
                <ul>
                    <?php foreach($comment_goods as $item):?>
                    <li class="goods-item">
                        <div class="thumb">
                            <a href="<?=$this->url('./goods', ['id' => $item['item_id']])?>"><img src="<?=$item->goods->thumb?>" alt=""></a>
                        </div>
                        <div class="comment-item">
                            <div class="item-top">
                                <span><?=$item->user->name?></span>
                                <span><?=$item->created_at?></span>
                            </div>
                            <div class="item-middle">
                                <span class="name"><?=$item->goods->name?></span>
                                <span class="price"><?=$item->goods->price?></span>
                            </div>
                            <div class="item-content">
                                <?=$item->content?>
                            </div>
                        </div>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="slider-next">&gt;</div>
        </div>
    </div>
</div>



<!-- 弹出 app 下载图 -->
<!-- <div class="app-down-guide">
    <div class="content" style="background: url(//yanxuan.nosdn.127.net/e074a1c3c359701e236e712516189125.png);">
        <i class="fa fa-close"></i>
    </div>
</div> -->