<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '圈子';
$this->set([
    'keywords' => 'zodream圈子',
    'description' => 'zodream程序的讨论圈子、意见反馈区'
])->extend('layouts/header');
?>

<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li class="active">
            圈子首页
        </li>
    </ul>
</div>

<div class="container">
    <?php foreach($forum_list as $group):?>
    <div class="forum-group">
        <div class="group-header">
            <a href="<?=$this->url('./forum', ['id' => $group->id])?>"><?=$group['name']?></a>
        </div>
        <div class="group-body">
            <?php foreach($group['children'] as $item):?>
            <div class="forum-item">
                <div class="thumb">
                    <img src="<?=$this->asset('images/zd.jpg')?>" alt="">
                </div>
                <div class="info">
                    <div class="name">
                        <a href="<?=$this->url('./forum', ['id' => $item->id])?>"><?=$item['name']?></a>
                        <?php if($item->today_count > 0):?>
                        <span>(<?=$item->today_count?>)</span>
                        <?php endif;?>
                    </div>
                    <div class="count">主题：<?=$item->thread_count?>，帖数：<?=$item->post_count?></div>
                    <?php if($item->last_thread):?>
                    <div class="last-thread">
                        <a href="<?=$this->url('./thread', ['id' => $item->last_thread->id])?>"><?=$this->text($item->last_thread->title, 10)?></a>
                        <?=$item->last_thread->updated_at?> <?=$this->text($item->last_thread->user->name)?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <?php endforeach;?>
</div>
