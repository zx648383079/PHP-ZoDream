<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Json;
/** @var $this View */
$lang = [
    'side_title' => __('Catalog'),
    'reply_btn' => __('Reply'),
    'reply_title' => __('Reply Comment'),
    'comment_btn' => __('Comment'),
    'comment_title' => __('Leave A Comment')
];
$lang = Json::encode($lang);
?>
<?php if (!empty($hot_comments)): ?>
<div class="book-comments hot-comments">
    <div class="title">
        <?=__('Hottest Comments')?>
    </div>
    <?php foreach ($hot_comments as $item) :?>
    <div class="comment-item" data-id="<?=$item->id?>">
        <div class="info">
            <span class="user"><?=$this->text($item['name'])?></span>
            <span class="time"><?=$item['created_at']?></span>
            <span class="floor"><?=$item->position?><?=__('floor')?></span>
        </div>
        <div class="content">
            <p><?=$this->text($item['content'])?></p>
            <?php if ($item->reply_count > 0):?>
            <span class="expand"><?=__('Expand ({count})', ['count' => $item->reply_count])?></span>
            <?php endif;?>
            <span>&nbsp;</span>
            <span class="comment" data-type="reply"><i class="fa fa-comment"></i></span>
            <span class="report"><?=__('Report')?></span>
            <div class="actions">
                <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$item['agree_count']?></b></span>
                <span class="disagree"><i class="fas fa-thumbs-down"></i><b><?=$item['disagree_count']?></b></span>
            </div>
        </div>
        
        <div class="comments">

        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<?php if ($comment_status === 1 || ($comment_status === 2 && !auth()->guest())):?>
    <div class="book-comment-form">
        <div class="title">
            <?=__('Leave A Comment')?>
        </div>
        <form id="comment-form" method="post" action="<?=$this->url('./comment/save', false)?>">
            <input type="hidden" name="blog_id" value="<?=$blog_id?>">
            <input type="hidden" name="parent_id">
            <?php if (auth()->guest()):?>
                <div class="form-table">
                    <div class="form-group">
                        <label><?=__('Nick Name')?></label>
                        <input type="text" name="name" placeholder="<?=__('Please input your nick name')?>">
                    </div>
                    <div class="form-group">
                        <label><?=__('Email')?></label>
                        <input type="email" name="email" placeholder="<?=__('Please input your email')?>">
                    </div>
                    <div class="form-group">
                        <label><?=__('URL')?></label>
                        <input type="url" name="url" placeholder="<?=__('Please input your URL')?>">
                    </div>
                </div>
            <?php endif; ?>
            <textarea name="content" placeholder="<?=__('Please input the content')?>"></textarea>
            <button class="btn-submit"><?=__('Comment')?></button>
            <button type="button" class="btn-cancel"><?=__('Cancel')?></button>
        </form>
    </div>
<?php elseif ($comment_status === 2):?>
<div class="comment-tip">
    请先
    <a href="<?=$this->url('/auth', ['redirect_uri' => $this->url()])?>">登录</a>
    才能评论
</div>
<?php endif;?>
<div class="book-comments">
    <div class="title">
        <?=__('All Comments')?>
        <div class="order">
            <span class="active"><?=__('New')?></span>
            <span><?=__('Old')?></span>
        </div>
    </div>
    <div id="comment-box">
    
    </div>
</div>

<script>
bindBlogComment('<?=$blog_id?>', <?=$lang?>);
</script>