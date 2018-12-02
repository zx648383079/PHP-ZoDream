<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '搜索';
$this->extend('../layouts/search_back');
?>


<div class="has-header">

    <div class="goods-list">
        <?php $this->extend('./page');?>
    </div>

    <?php if($goods_list->hasMore()):?>
    <div class="more-load" data-page="<?=$goods_list->getIndex()?>" data-target=".goods-list" data-url="<?=$this->url(['page' => null])?>">
    加载中。。。
    </div>
    <?php endif;?>
    

</div>
