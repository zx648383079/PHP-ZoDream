<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('Archives');
$this->extend('layouts/header');
?>

<div class="book-title">
    <ul class="book-nav">
        <li class="active">
            <a href="<?=$this->url('./')?>"><?=__('Archives')?></a></li>
    </ul>
</div>

<div class="book-body">
    <div class="book-sort">
        <a href="<?=$this->url('./category')?>"><?=__('Categories')?></a>
        <a href="<?=$this->url('./tag')?>"><?=__('Tags')?></a>
        <a class="active" href="<?=$this->url('./archives')?>"><?=__('Archives')?></a>
    </div>
    <div class="time-axis">
        <?php foreach ($blog_list as $year => $items):?>
        <div class="time-title">
            <div class="time-year"><?=$year?></div>
        </div>
        <div class="time-items">
            <?php foreach ($items as $item):?>
            <a class="time-item" href="<?=$this->url('./', ['id' => $item['id']])?>">
                <div class="title"><?=$item['title']?></div>
                <div class="time"><?=$item['date']?></div>
            </a>
            <?php endforeach;?>
        </div>
        <?php endforeach;?>
    </div>
    
</div>
    
<?php $this->extend('layouts/footer');?>