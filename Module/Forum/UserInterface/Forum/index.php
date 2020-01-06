<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $forum->name;
$this->registerCssFile([
        '@forum.css',
    ])
    ->registerJsFile([
        '@forum.min.js'
    ]);
?>

<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>">圈子首页</a>
        </li>
        <?php foreach($path as $item):?>
        <li>
            <a href="<?=$this->url('./forum', ['id' => $item->id])?>"><?=$item->name?></a>
        </li>
        <?php endforeach;?>
        <li class="active">
            <a href="<?=$this->url('./forum', ['id' => $forum->id])?>"><?=$forum->name?></a>
        </li>
    </ul>
</div>

<div class="container">
    <div class="forum-group">
        <div class="group-header">
            <?=$forum->name?>
        </div>
        <div class="group-body">
            <?php foreach($forum_list as $item):?>
            <div class="forum-item">
                <div class="thumb">
                    <img src="<?=$this->asset('images/zd.jpg')?>" alt="">
                </div>
                <div class="info">
                    <div class="name">
                        <a href="<?=$this->url('./forum', ['id' => $item->id])?>"><?=$item['name']?></a>
                        <span>(1)</span>
                    </div>
                    <div class="count">主题：<?=$item->thread_count?>，帖数：<?=$item->post_count?></div>
                    <?php if($item->last_thread):?>
                    <div class="last-thread">
                        <a href="<?=$this->url('./thread', ['id' => $item->last_thread->id])?>"><?=$this->text($item->last_thread->title, 10)?></a>
                        <?=$item->last_thread->updated_at?> <?=$item->last_thread->user->name?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>

    <div class="thread-list">
        <?php $this->extend('./page');?>
    </div>

    <?php if(!auth()->guest() && $forum->parent_id > 0):?>
    <div class="thread-new-box">
        <div class="header">快速发帖</div>
        <form data-type="ajax" action="<?=$this->url('./thread/create')?>" method="post">
            <div class="title">
                <select name="classify_id">
                    <option value="0">选择主题分类</option>
                    <?php foreach($classify_list as $item):?>
                    <option value="<?=$item->id?>"><?=$item->name?></option>
                    <?php endforeach;?>
                </select>
                <input type="text" name="title" required>
            </div>
            
            <div class="editor">
                <textarea name="content" required></textarea>
            </div>
            <div class="footer">
                <button class="btn">发表帖子</button>
                <input type="hidden" name="forum_id" value="<?=$forum->id?>">
            </div>
        </form>
    </div>
    <?php endif;?>
</div>
