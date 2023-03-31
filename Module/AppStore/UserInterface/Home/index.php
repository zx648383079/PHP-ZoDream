<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Disk;
/** @var $this View */
$this->title = 'DEMO';
$this->registerCssFile('@demo.css')
    ->registerJsFile('@demo.min.js')->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>


<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li class="active">
            <a href="<?=$this->url('./')?>">应用商店首页</a>
        </li>
    </ul>
</div>

<div class="container">
    <div class="tab-bar">
        <a href="<?=$this->url('./')?>">全部</a>
        <?php foreach($cat_list as $item):?>
            <a href="<?=$this->url('./', ['category' => $item['id']])?>" <?=$category == $item['id'] ? 'class="active"' : ''?>><?=$item['name']?></a>
        <?php endforeach;?>
    </div>

    <?php foreach($app_list as $item):?>
        <div class="post-item">
            <div class="item-thumb">
                <a href="<?=$this->url('./', ['id' => $item['id']])?>"><img src="<?=$item->icon?>" alt=""></a>
            </div>
            <div class="item-body">
                <div class="title"><a href="<?=$this->url('./', ['id' => $item['id']])?>"><?=$item->title?></a></div>
                <?php if($item->category):?>
                <a class="link-btn" href="<?=$this->url('./', ['category' => $item['cat_id']])?>"><?=$item->category->name?></a>
                <?php endif;?>
                <p><?=$item->description?></p>
            </div>
            <div class="item-footer">
                <div class="line-item">
                    <i class="fa fa-calendar-check"></i>
                    更新时间：<?=$this->ago($item->getAttributeSource('created_at'))?>
                </div>
                <div class="line-item">
                    <i class="fa fa-file"></i>
                    文件大小：<?=Disk::size($item->size)?>
                </div>
                <div class="bottom">
                    <a class="link-btn" href="<?=$this->url('./download', ['id' => $item['id']])?>" target="_blank">下载</a>
                </div>
            </div>
        </div>
    <?php endforeach;?>

    <div class="pager">
        <?=$app_list->getLink()?>
    </div>
</div>
