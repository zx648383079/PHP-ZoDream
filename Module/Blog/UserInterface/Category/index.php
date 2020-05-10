<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('Categories');
$this->set([
    'keywords' => __('Categories'),
    'description' => __('Categories')
])->extend('layouts/header');
?>

<div class="book-title">
    <ul class="book-nav">
        <li class="active">
            <a href="<?=$this->url('./')?>"><?=__('Categories')?></a></li>
    </ul>
</div>

<div class="book-body">
    <div class="book-sort">
        <a class="active" href="<?=$this->url('./category')?>"><?=__('Categories')?></a>
        <a href="<?=$this->url('./tag')?>"><?=__('Tags')?></a>
        <a href="<?=$this->url('./archives')?>"><?=__('Archives')?></a>
    </div>
    <?php foreach ($cat_list as $item):?>
    <div class="cat-item">
        <?php if($item['level'] > 0):?>
        <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?></span>
        <?php endif;?>
        <a href="<?=$this->url('./', ['category' => $item['id']])?>"><?=$item['name']?></a>
        <span class="count">(<?=$item['blog_count']?>)</span>
    </div>
    <?php endforeach;?>
</div>
    
<?php $this->extend('layouts/footer');?>