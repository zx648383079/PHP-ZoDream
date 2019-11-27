<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $goods->name;
$js = <<<JS
bindGoods({$goods->id});
JS;
$this->registerCssFile('@slider.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="goods-page">
    <div class="container">
        <ul class="path">
            <li>
                <a href="<?=$this->url('./')?>">首页</a>
            </li>
            <li>
                <a href="<?=$this->url('./category', ['id' => $goods->cat_id])?>">
                    <?=$goods->category->name?>
                </a>
            </li>
            <li>
                <?=$goods->name?>
            </li>
        </ul>

        <div class="info-box">
            <div class="picture-box">
                <div class="view">
                    <img src="<?=$goods->picture?>" alt="">
                </div>
                <ul>
                    <li><img src="<?=$goods->picture?>" alt=""></li>
                    <?php foreach($gallery_list as $item):?>
                   <li><img src="<?=$item->image?>" alt=""></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div>
                <div class="intro">
                    <div class="name">
                        <?=$goods->name?>
                        <a href="" class="comment">
                            <span>98%</span>
                            <span>好评率</span>
                        </a>
                    </div>
                    <div class="desc">
                        让双脚每天享受15分钟桑拿
                        
                    </div>
                </div>
                <div class="price-box">
                    <div>活动价</div>
                    <div class="price">￥<?=$goods->price?></div>
                    <div>领券</div>
                    <div></div>
                    <div>促销</div>
                    <div></div>
                    <div>积分</div>
                    <div>购买最高得42积分</div>
                    <div>配送</div>
                    <div>至</div>
                    <hr>
                    <div>服务</div>
                    <div>
                        ･ 支持30天无忧退换货    
                        ･ 48小时快速退款    
                        ･ 满88元免邮费    
                        ･ 网易自营品牌    
                        ･ 国内部分地区无法配送 
                    </div>
                </div>
                <div class="property-box">

                    <div>数量</div>
                    <div>
                        <div class="number-box">
                            <i class="fa fa-minus"></i>
                            <input type="text" class="number-input" value="1">
                            <i class="fa fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <a href="javascript:;" data-type="buy" class="btn">
                    立即购买
                    </a>
                    <a href="javascript:;" data-type="addCart" class="btn btn-primary">
                        <i class="fa fa-shopping-cart"></i>
                        加入购物车
                    </a>
                    <a  href="javascript:;" data-type="collect" class="btn btn-collect">
                        <i class="fa fa-star"></i>
                        收藏
                    </a>
                </div>
            </div>
        </div>

        <div class="template-lazy" data-url="<?=$this->url('./goods/recommend', ['id' => $goods->id])?>" data-tpl="recommend_tpl">
        </div>

        <div class="detail-box">
            <div class="tab-box">
                <div class="tab-header">
                    <div class="tab-item active">
                    详情
                    </div>
                    <div class="tab-item">
                    评价
                    <?php if($goods->comment_count > 0):?>
                    (<?=$goods->comment_count?>)
                    <?php endif;?>
                    </div>
                    <div class="tab-item">
                    常见问题
                    </div>
                </div>
                <div class="tab-body">
                    <div class="tab-item active">
                        <div class="content-box">
                        <?=$goods->content?>
                        </div>
                    </div>
                    <div id="comment-tab" class="tab-item" data-url="<?=$this->url('./goods/comment', ['id' => $goods->id])?>">
                        
                    </div>
                    <div class="tab-item issue-box" data-url="<?=$this->url('./goods/issue', ['id' => $goods->id])?>">
                        
                    </div>
                </div>
            </div>

            <div class="template-lazy" data-url="<?=$this->url('./goods/hot', ['id' => $goods->id])?>" data-tpl="hot_tpl">
            </div>
            
        </div>
    </div>
</div>

<?php $this->extend([
    './recommend.tpl',
    './hot.tpl'
]);?>