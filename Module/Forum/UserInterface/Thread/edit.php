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
        <li>
            <a href="<?=$this->url('./thread', ['id' => $thread->id])?>"><?=$thread->title?></a>
        </li>
        <li class="active">
            编辑
        </li>
    </ul>
</div>

<div class="container">
    <div class="thread-new-box">
        <div class="header">编辑帖子【<?=$thread->title?>】</div>
        <form data-type="ajax" action="<?=$this->url('./thread/update')?>" method="post">
            <div class="title">
                <select name="classify_id">
                    <option value="0">选择主题分类</option>
                    <?php foreach($classify_list as $item):?>
                    <?php if($item->id == $thread->classify_id):?>
                        <option value="<?=$item->id?>" selected><?=$item->name?></option>
                    <?php else:?>
                        <option value="<?=$item->id?>"><?=$item->name?></option>
                    <?php endif;?>
                    <?php endforeach;?>
                </select>
                <input type="text" name="title" required value="<?=$thread->title?>">
            </div>
            
            <div class="editor">
                <textarea name="content" required><?=$post->content?></textarea>
            </div>
            <div class="footer">
                <button class="btn">保存帖子</button>
                <input type="hidden" name="id" value="<?=$thread->id?>">
            </div>
        </form>
    </div>

</div>

