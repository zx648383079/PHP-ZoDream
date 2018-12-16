<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="banner">
    <img src="<?=$category->app_banner?>" alt="">
</div>
<div class="header">
    <a href="<?=$this->url('./mobile/search', ['cat_id' => $category->id])?>"><?=$category->name?></a>
</div>
<div class="goods-list">
    <?php foreach($hot_list as $item):?>
    <div class="item-view">
        <div class="item-img">
            <a href="<?=$this->url('./mobile/goods', ['id' => $item->id])?>"><img src="<?=$item->thumb?>" alt=""></a>
        </div>
        <div class="item-title">
            <?=$item->name?>
        </div>
        <div class="item-actions">
            <span class="item-price"><?=$item->price?></span>
            
        </div>
    </div>
    <?php endforeach;?>
</div>
<ul class="tree-grid">
    <?php foreach($category_tree as $item):?>
    <li class="tree-item">
        <a href="<?=$this->url('./mobile/search', ['cat_id' => $item['id']])?>"><?=$item['name']?></a>
        <?php if(isset($item['children'])):?>
        <ul class="tree-item-chidren">
            <?php foreach($item['children'] as $it):?>
            <li class="tree-item">
                <a href="<?=$this->url('./mobile/search', ['cat_id' => $it['id']])?>"><?=$it['name']?></a>
            </li> 
            <?php endforeach;?>
        </ul>
        <?php endif;?>
    </li>
    <?php endforeach;?>
</ul>