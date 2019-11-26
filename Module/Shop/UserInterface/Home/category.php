<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
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