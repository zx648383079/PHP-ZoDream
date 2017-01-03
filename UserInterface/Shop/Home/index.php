<?php
defined('APP_DIR') or exit();
use Domain\Model\Advertisement\AdModel;
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');
?>
<!-- 首页轮播图 --->
<div class="banner">
    <div class="slider">
        <?php foreach (AdModel::getAds(2) as $item):?>
            <div>
                <?=$item->getHtml()?>
            </div>
        <?php endforeach;?>
    </div>
</div>
<!-- 热门、推荐、楼层商品 --->
<?php foreach ($this->allGoods as $groupGoods):?>
    <div class="groupGoup">
        <div class="title">
            <div><?=$groupGoods['title']?></div>
            <div class="right">
                <?=Html::a('更多', $groupGoods['url'])?>
            </div>
        </div>
        <div class="content">
            <?php foreach ($groupGoods['goods'] as $goods):?>
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
    </div>
    <?php if ($groupGoods['ad']):?>
        <div class="ad">
            <?=AdModel::getAd($groupGoods['ad'])?>
        </div>
    <?php endif;?>
<?php endforeach;?>

<?php
$this->extend('layout/footer');
?>