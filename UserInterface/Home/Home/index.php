<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('home');
$this->set([
    'keywords' => __('site keywords'),
    'description' => __('site description')
]);
?>

<div class="container">
    <div class="metro-grid">
        <a href="<?=$this->url('blog')?>">
            <?=__('blog')?>
        </a>
        <a href="<?=$this->url('doc')?>">
            <?=__('Document')?>
        </a>
        <a href="<?=$this->url('tool')?>">
            <?=__('Tool')?>
        </a>
        <a href="<?=$this->url('forum')?>">
            <?=__('Forum')?>(DEMO)
        </a>
        <a href="<?=$this->url('shop')?>">
            <div class="name"><?=__('Shop')?>(DEMO)</div>
            <p class="desc"><?=__('shop tip')?></p>
        </a>
        <a class="unknow" href="<?=$this->url('cms')?>">
            <img src="<?=$this->asset('images/zd_seo.jpg')?>" alt="">
            CMS(DEMO)
        </a>
    </div>

    <div class="panel">
        <div class="panel-header">
            <?=__('Latest Blog')?>
        </div>
        <div class="panel-body">
            <?=$this->node('blog-panel', ['limit' => 6])?>
        </div>
    </div>
</div>
