<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('Tags');
$this->extend('layouts/header');
?>

<div class="book-title">
    <ul class="book-nav">
        <li class="active">
            <a href="<?=$this->url('./')?>"><?=__('Tags')?></a></li>
    </ul>
</div>

<div class="book-body">
    <div class="book-sort">
        <a href="<?=$this->url('./category')?>"><?=__('Categories')?></a>
        <a class="active" href="<?=$this->url('./tag')?>"><?=__('Tags')?></a>
        <a href="<?=$this->url('./archives')?>"><?=__('Archives')?></a>
    </div>
    <?php foreach ($tag_list as $item):?>
    <a class="tag-item" href="<?=$this->url('./', ['tag' => $item['name']])?>" style="font-size: <?=$item['blog_count'] + 12?>px"><?=$item['name']?></a>
    <?php endforeach;?>
</div>
    
<?php $this->extend('layouts/footer');?>