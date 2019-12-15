<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<form class="forward-box" action="<?=$this->url('./forward', false)?>" method="post">
    <div class="simple">
        @<?=$blog->user->name?> ：<?=$blog->content?>
    </div>
    <div class="input">
        <textarea name="content" placeholder="请输入转发理由" required></textarea>
    </div>
    <div class="actions">
        <div class="tools">
            <i class="fa fa-smile"></i>
            <i class="fa fa-image"></i>
        </div>
        <input type="checkbox" name="is_comment">同时评论给 <?=$blog->user->name?>
        <button type="button" class="dialog-yes">转发</button>
        <input type="hidden" name="id" value="<?=$blog->id?>">
    </div>
</form>