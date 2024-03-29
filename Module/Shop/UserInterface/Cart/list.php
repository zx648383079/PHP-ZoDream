<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$js = <<<JS
bindCart();
JS;
$this->registerJs($js);
?>
<div class="cart-header">
    <div class="chkbox">
        <input type="checkbox" class="check-box">
        <span>全选</span>
    </div>
    <div>商品信息</div>
    <div>单价</div>
    <div>数量</div>
    <div>小计</div>
    <div>操作</div>
</div>

<?php foreach($cart->toGroupArray() as $group):?>
<div class="cart-group-item">
    <div class="group-header">
    <?=$group['name']?>
    </div>
    <?php foreach($group['goods_list'] as $item):?>
    <div class="cart-item" data-id="<?=$item->id?>">
        <div class="chk">
            <input type="checkbox" class="check-box">
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
                    <input type="text" class="number-input" value="<?=$item->amount?>">
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
        <input type="checkbox" class="check-box">
        <span>
            <span>已选（</span><span class="cart-checked-count">0</span>
            <span>）</span>
        </span>
        <a>批量删除</a>
        <a>清空失效商品</a>
    </div>
    <div class="total">
        <span>SUBTOTAL : </span>
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
        <a href="<?=$this->url('./cashier')?>" class="btn">CHECKOUT</a>
    </div>
</div>