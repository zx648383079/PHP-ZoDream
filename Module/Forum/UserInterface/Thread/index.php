<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $thread->title;
$this->extend('layouts/header');
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
            <a href="<?=$this->url('./thread', ['id' => $thread->id])?>"><?=$thread->title?></a>
        </li>
    </ul>
</div>

<div class="container">
    <div class="thread-box">
        <div class="thread-title<?=$thread->is_highlight ? ' thread-highlight' : ''?>">
            <div class="count">
                <span>查看：<?=$thread->view_count?></span>
                <span>回复：<?=$thread->post_count?></span>
            </div>
            <div class="title">
                <?php if($thread->classify):?>
                [
                    <a href=""><?=$thread->classify->name?></a>
                ]
                <?php endif;?>
                <?=$this->text($thread->title)?>
                <?php if($thread->is_digest):?>
                <i class="fa fa-fire"></i>
                <?php endif;?>
            </div>
        </div>
        <?php $this->extend('./page');?>

        <?php if(!auth()->guest() && !$thread->is_closed):?>
        <div class="post-item post-new">
            <div class="post-user">
                <div class="name"><?=auth()->user()->name?></div>
                <div class="avatar">
                    <img src="<?=auth()->user()->avatar?>" alt="">
                </div>
            </div>
            <div class="post-content">
                <form data-type="ajax" action="<?=$this->url('./thread/reply')?>" method="post">
                    <div class="editor">
                        <textarea name="content" required></textarea>
                    </div>
                    <div class="footer">
                        <button class="btn">发表回复</button>
                        <input type="hidden" name="thread_id" value="<?=$thread->id?>">
                    </div>
                </form>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>
