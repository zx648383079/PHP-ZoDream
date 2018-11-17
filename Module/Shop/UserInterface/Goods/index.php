<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $goods->name;
$js = <<<JS
$(".slider-goods").slider({
    haspoint: false
});
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
                            <input type="text" class="number-input">
                            <i class="fa fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <a href="" class="btn">
                    立即购买
                    </a>
                    <a href="" class="btn btn-primary">
                        <i class="fa fa-shopping-cart"></i>
                        加入购物车
                    </a>
                    <a href="" class="btn btn-collect">
                        <i class="fa fa-star"></i>
                        收藏
                    </a>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                大家都在看
            </div>
            <div class="panel-body">
                <div class="slider slider-goods" data-height="279" data-width="210">
                    <div class="slider-previous">&lt;</div>
                    <div class="slider-box">
                        <ul>
                            <?php foreach($hot_goods as $item):?>
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

        <div class="detail-box">
            <div class="tab-box">
                <div class="tab-header">
                    <div class="tab-item active">
                    详情
                    </div>
                    <div class="tab-item">
                    评价()
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
                    <div class="tab-item">
                        <div class="comment-box">
                            <div class="comment-header">
                                <div class="text-center">
                                好评率
                                </div>
                                <div>
                                大家都在说：
                                </div>
                                <div class="text-center">
                                    <div class="rate">91%</div>
                                    <div class="score">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                                <div class="tag-box">
                                    <span class="active">全部（111）</span>
                                    <span>有图（24）</span>
                                    <span>追评（3）</span>
                                    <span>有效实用（11）</span>
                                </div>
                            </div>
                            <div class="comment-filter">
                                <span>排序</span>
                                <a href="" class="active"> 默认</a>
                                <a href="">评价时间</a>
                            </div>
                            <?php foreach($comment_list as $item):?>
                            <div class="comment-item">
                                <div class="user-box">
                                    <div class="avatar">
                                        <img src="<?=$item->user->avatar?>" alt="">
                                    </div>
                                    <div class="name"><?=$item->user->name?></div>
                                </div>
                                <div>
                                    <div class="score">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="attr">
                                    规格:雾白
                                    </div>
                                    <div class="content"><?=$item->content?></div>
                                    <ul class="image-box">
                                        <?php foreach($item->images as $img):?>
                                        <li>
                                            <img src="<?=$img->image?>" alt="">
                                        </li>
                                        <?php endforeach;?>
                                    </ul>
                                    <div class="time"><?=$item->created_at?></div>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="tab-item issue-box">
                        <ul>
                        <?php foreach($issue_list as $item):?>
                            <li class="issue">
                                <div class="question"><?=$item['question']?></div>
                                <div class="answer"><?=$item['answer']?></div>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    24小时热销榜
                </div>
                <div class="panel-body">
                    <?php foreach($hot_goods as $item):?>
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
    </div>
</div>