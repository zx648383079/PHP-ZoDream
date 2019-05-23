<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '本地主题';
?>
<div class="panel">
    <div class="panel-header">当前主题</div>
    <div class="panel-body">
        <div class="theme-item">
            <div class="thumb">
                <img src="<?=$current['cover']?>" alt="">
            </div>
            <div class="name"><?=$current['name']?></div>
            <div class="desc"><?=$current['description']?></div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">本地主题</div>
    <div class="panel-body">
        <?php foreach($themes as $item):?>
        <div class="theme-item">
            <div class="thumb">
                <img src="<?=$item['cover']?>" alt="">
            </div>
            <div class="name"><?=$item['name']?></div>
            <div class="desc"><?=$item['description']?></div>
            <a href="" class="btn">使用</a>
        </div>
        <?php endforeach;?>
    </div>
</div>
