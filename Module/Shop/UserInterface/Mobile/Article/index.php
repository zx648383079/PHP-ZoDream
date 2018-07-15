<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '文章分类';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <div class="item-list">
        <?php foreach($model_list as $item):?>
        <a href="<?=$this->url('./mobile/article/category', ['id' => $item->id])?>">
            <?=$item->name?>
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </a>
        <?php endforeach;?>

    </div>
    
</div>
