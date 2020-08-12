<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '服务';
$this->extend('layouts/main');
?>

<div class="container main-box">
    <div class="tab-bar">
        <a href="<?=$this->url('./')?>" class="<?=$category < 1 ? 'active' : ''?>">全部</a>
        <?php foreach($cat_list as $item):?>
        <a href="<?=$this->url('./', ['category' => $item['id']])?>"  class="<?=$category == $item['id'] ? 'active' : ''?>"><?=$item['name']?></a>
        <?php endforeach;?>
        <a href="<?=$this->url('./order')?>">我的订单</a>
    </div>

    <?php foreach($model_list as $item):?>
    <div class="service-item">
        <div class="title">
            <span class="tag">[<?=$item->category->name?>]</span>
            <?=$item->name?>
        </div>
        <div class="brief">
            <?=$item->brief?>
        </div>
        <a href="<?=$this->url('./order/create', ['id' => $item->id])?>" title="<?=$item->name?>" class="btn">
            <span>我要下单</span>
        </a>
    </div>
    <?php endforeach;?>

    <div>
        <?=$model_list->getLink()?>
    </div>
</div>

