<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
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

<?php foreach($cart as $group):?>
<div class="cart-group">
    <div class="promotion-wrap">
    <?=$group->getName()?>
    </div>
    <?php foreach($group as $item):?>
    <div class="cart-item" data-id="<?=$item->id?>">
        <div class="chk">
            <input type="checkbox">
        </div>
        <div class="thumb">
            <img src="<?=$item->goods->thumb?>" alt="">
        </div>
        <div>
            <div class="name"><?=$item->goods->name?></div>
            <div class="attr">白色</div>
        </div>
        <div class="price">
            <span><?=$item->price?></span>
        </div>
        <div>
            <div class="number-box">
                    <i class="fa fa-minus"></i>
                    <input type="text" class="number-input" value="<?=$item->number?>">
                    <i class="fa fa-plus"></i>
                </div>
        </div>
        <div class="total">
            <span><?=$item->total?></span>
        </div>
        <div class="actions">
            <a href="">移入收藏夹</a>
            <a href="">删除</a>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endforeach;?>

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
        <span class="price">￥<?=$cart->total()?></span>
        <span>活动优惠 : </span>
        <span class="price">￥0</span>
    </div>
    <div class="money">
        <span>应付总额：</span>
        <span class="price">¥ <?=$cart->total()?></span>
        <div class="tip">已满足免邮条件></div>
    </div>
    <div class="checkout">
        <a href="<?=$this->url('./cashier')?>">下单</a>
    </div>
</div>