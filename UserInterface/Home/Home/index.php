<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('home');
$this->set([
    'keywords' => __('site keywords'),
    'description' => __('site description')
]);
$module_list = [
    [
        'blog',
        __('blog')
    ],
    [
        'doc',
        __('Document')
    ],
    [
        'tool',
        __('Tool')
    ],
    [
        'code',
        __('Code Block')
    ],
    [
        'exam',
        __('Exam')
    ]
];
$demo_list = [
    [
        'forum',
        __('Forum')
    ],
    [
        'micro',
        __('Micro Blog')
    ],
    [
        'book',
        __('Book')
    ],
    [
        'cms',
        __('CMS')
    ],
    [
        'finance',
        __('Finance')
    ],
    // [
    //     'shop',
    //     __('Shop')
    // ],
    [
        'task',
        __('Task')
    ],
    // [
    //     'wx',
    //     __('WeChat')
    // ]
];
?>

<div class="container">
    <div class="metro-grid">
       <?php foreach($module_list as $item):?>
       <a href="<?=$this->url($item[0])?>">
            <?=$item[1]?>
        </a>
       <?php endforeach;?>
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

    <div class="panel">
        <div class="panel-header">
            <?=__('Demo')?>
            <small><?= __('Demo Tip') ?></small>
        </div>
        <div class="panel-body">
            <div class="metro-grid">
            <?php foreach($demo_list as $item):?>
            <a href="<?=$this->url($item[0])?>">
                    <?=$item[1]?>
                </a>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
