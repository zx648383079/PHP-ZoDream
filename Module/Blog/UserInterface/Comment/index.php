<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php if (!empty($hot_comments)): ?>
<div class="book-comments hot-comments">
    <div class="title">
        热门评论
    </div>
    <?php foreach ($hot_comments as $item) :?>
    <div class="comment-item" data-id="<?=$item->id?>">
        <div class="info">
            <span class="user"><?=$this->text($item['name'])?></span>
            <span class="time"><?=$item['created_at']?></span>
            <span class="floor"><?=$item->position?>楼</span>
        </div>
        <div class="content">
            <p><?=$this->text($item['content'])?></p>
            <?php if ($item->reply_count > 0):?>
            <span class="expand">展开（<?=$item->reply_count?>）</span>
            <?php endif;?>
            <span>&nbsp;</span>
            <span class="comment" data-type="reply"><i class="fa fa-comment"></i></span>
            <span class="report">举报</span>
        </div>
        <div class="actions">
            <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$item['agree']?></b></span>
            <span class="disagree"><i class="fas fa-thumbs-down"></i><b><?=$item['disagree']?></b></span>
        </div>
        <div class="comments">

        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<div class="book-comment-form">
    <div class="title">
        发表评论
    </div>
    <form id="comment-form" method="post" action="<?=$this->url('./comment/save', false)?>">
        <input type="hidden" name="blog_id" value="<?=$blog_id?>">
        <input type="hidden" name="parent_id">
        <?php if (auth()->guest()):?>
        <div class="form-table">
            <div class="form-group">
                <label>姓名</label>
                <input type="text" name="name" placeholder="请输入姓名">
            </div>
            <div class="form-group">
                <label>邮箱</label>
                <input type="email" name="email" placeholder="请输入邮箱">
            </div>
            <div class="form-group">
                <label>网址</label>
                <input type="url" name="url" placeholder="请输入网址">
            </div>
        </div>
        <?php endif; ?>
        <textarea name="content" placeholder="请输入评论内容"></textarea>
        <button class="btn-submit">评论</button>
        <button type="button" class="btn-cancel">取消</button>
    </form>
</div>
<div class="book-comments">
    <div class="title">
        全部评论
        <div class="order">
            <span class="active">最新</span>
            <span>最早</span>
        </div>
    </div>
    <div id="comment-box">
    
    </div>
</div>

<script>
bindBlogComment('<?=$this->url('./', false)?>', '<?=$blog_id?>');
</script>