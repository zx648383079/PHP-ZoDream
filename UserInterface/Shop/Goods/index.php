<?php
defined('APP_DIR') or exit();
use Domain\Model\Advertisement\AdModel;
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Template\View */
/** @var $goods \Domain\Model\Shopping\GoodsModel */
$this->extend('layout/header');
?>

<!-- 商品介绍 --->
<div class="goods">
    <div class="goodsImage">
        <div class="large">
            <img src="<?=$goods->image?>">
        </div>
        <div class="slider">
            <ul>
                <li><img src="<?=$goods->image?>"></li>
                <?php foreach ($goods->getImages() as $item):?>
                    <li><img src="<?=$item->file?>"></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div class="goodsShow">
        <div class="title">
            <?=$goods->name?>
        </div>
        <div class="desc">
            <?=$goods->description?>
        </div>
        <div class="price">
            <p>原价：<?=$goods->market_price?></p>
            <p>促销价：<?=$goods->price?></p>
        </div>
        <div class="number">
            <input type="number" value="1"> (库存：<?=$goods->number?>)
        </div>
        <div class="buy">
            <button>立即购买</button>
            <button>加入购物车</button>
        </div>
    </div>
</div>
    <!-- 商品详情 --->
<div class="content">
    <div class="header">
        <div class="active">图文介绍</div>
        <div>规格</div>
        <div>评论</div>
    </div>
    <div class="body">
        <div class="active">
            <?=htmlspecialchars_decode($goods->content)?>
        </div>
        <div>
           <?php foreach ($goods->getProperties() as $item):?>
               <div class="row">
                   <div><?=$item->name?></div>
                   <div><?=$item->value?></div>
               </div>
            <?php endforeach;?>
        </div>
        <div>
            <div class="tags">
                <?php foreach ($goods->getTags() as $key => $item):?>
                    <a href=""><?=$key?>(<?=$item?>)</a>
                <?php endforeach;?>
            </div>
            <?php foreach ($goods->getComments() as $item):?>
                <div class="comment">
                    <div class="user">
                        <div class="avatar">
                            <img src="<?=$item['avatar']?>">
                        </div>
                        <p><?=$item['username']?></p>
                    </div>
                    <div class="content">
                        <div class="header">
                            <div><?=$item['star']?></div>
                            <div><?=$item['create_at']?></div>
                        </div>
                        <div>
                            <?=$item['content']?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>

<!-- 推荐商品 --->
<div class="recommendGoods">
    <?php foreach ($recommendGoods as $goods):?>
        <div class="goodsItem">
            <a class="<?=$goods['url']?>">
                <div class="img">
                    <?=Html::img($goods['thumb'])?>
                </div>
                <div>
                    <span><?=$goods['name']?></span>
                    <span><?=$goods['price']?></span>
                </div>
            </a>
        </div>
    <?php endforeach;?>
</div>

<?php
$this->extend('layout/footer');
?>