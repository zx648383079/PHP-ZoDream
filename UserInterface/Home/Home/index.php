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
        <a href="<?=$this->url('cms')?>">
            CMS(DEMO)
        </a>
        <a href="<?=$this->url('finance')?>">
            <?=__('Finance')?>
        </a>
        <a href="<?=$this->url('disk')?>">
            <?=__('Disk')?>
        </a>
        <a href="<?=$this->url('book')?>">
            <?=__('Book')?>
        </a>
        <a href="<?=$this->url('chat')?>">
            <?=__('Chat')?>
        </a>
        <a href="<?=$this->url('micro')?>">
            <?=__('Micro Blog')?>
        </a>
        <a href="<?=$this->url('note')?>">
            <?=__('Note')?>
        </a>
        <a href="<?=$this->url('check_in')?>">
            <?=__('Check In')?>
        </a>
        <a class="unknow" href="<?=$this->url('cms')?>">
            <img src="<?=$this->asset('images/zd_seo.jpg')?>" alt="">
            CMS(DEMO)
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="panel">
                <div class="panel-header">
                    <a href="<?=$this->url('./blog')?>"><?=__('Latest Blog')?></a>
                </div>
                <div class="panel-body">
                    <?=$this->node('blog-panel', ['limit' => 6])?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-header">
                    <a href="<?=$this->url('./note')?>"><?=__('Latest Note')?></a>
                </div>
                <div class="panel-body scoll-box">
                    <?=$this->node('note-panel', ['limit' => 12])?>
                    <a href="<?=$this->url('./note')?>" class="more"><?=__('View More...')?></a>
                </div>
            </div>
        </div>
    </div>
</div>
