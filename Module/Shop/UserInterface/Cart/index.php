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
$this->registerCssFile('@slider.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="cart-page">
    <div class="container">

        <div class="cart-header">
            <div class="chkbox">
                <input type="checkbox" checked="" >
                <span >全选</span>
            </div>
            <div>商品信息</div>
            <div>单价</div>
            <div>数量</div>
            <div>小计</div>
            <div>操作</div>
        </div>

        <div class="cart-group">
            <div class="promotion-wrap">
            已满足【满130元可超值换购】
            </div>
            <div class="cart-item">
                <div class="chk">
                    <input type="checkbox">
                </div>
                <div class="thumb">
                    <img src="http://yanxuan.nosdn.127.net/1a79d5657936406d1c7c5202b8b3eff9.png?quality=95&thumbnail=200x200&imageView" alt="">
                </div>
                <div>
                    <div class="name">网易智造碳纤维取暖器</div>
                    <div class="attr">白色</div>
                </div>
                <div class="price">
                    <span>249.00</span>
                </div>
                <div>
                    <div class="number-box">
                            <i class="fa fa-minus"></i>
                            <input type="text" class="number-input">
                            <i class="fa fa-plus"></i>
                        </div>
                </div>
                <div class="total">
                    <span>249.00</span>
                </div>
                <div class="actions">
                    <a href="">移入收藏夹</a>
                    <a href="">删除</a>
                </div>
            </div>
        </div>

        <div class="cart-footer">
            <div class="chkbox">
                <input type="checkbox">
                <span>
                    <span>已选（</span><span>2</span>
                    <span>）</span>
                </span>
                <a>批量删除</a>
                <a>清空失效商品</a>
            </div>
            <div class="total">
                <span>商品合计 : </span>
                <span class="price">￥11</span>
                <span>活动优惠 : </span>
                <span class="price">￥11</span>
            </div>
            <div class="money">
                <span>应付总额：</span>
                <span class="price">¥ 675.50</span>
                <div class="tip">已满足免邮条件></div>
            </div>
            <div class="checkout">
                <a href="<?=$this->url('./cashier')?>">下单</a>
            </div>
        </div>


        <div class="panel">
            <div class="panel-header">
            猜你喜欢
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