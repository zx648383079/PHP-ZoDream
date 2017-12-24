<?php
use Zodream\Template\View;
use Zodream\Domain\Access\Auth;
/** @var $this View */
?>
<?php if (!empty($hot_comments)): ?>
<div class="book-comments hot-comments">
    <div class="title">
        热门评论
    </div>
    <?php foreach ($hot_comments as $item) :?>
    <div class="comment-item">
        <div class="info">
            <span class="user"><?=$item['name']?></span>
            <span class="time"><?=$item['created_at']?></span>
            <span class="floor">4楼</span>
        </div>
        <div class="content">
            <p><?=$item['content']?></p>
            <span class="expand">展开（8）</span>
            <span>&nbsp;</span>
            <span class="comment"><i class="fa fa-comment"></i></span>
            <span class="report">举报</span>
        </div>
        <div class="actions">
            <span class="agree"><i class="fa fa-thumbs-o-up"></i><b><?=$item['agree']?></b></span>
            <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b><?=$item['disagree']?></b></span>
        </div>
        <div class="comments">
            <div class="comment-item">
                <div class="info">
                    <span class="user">admin</span>
                    <span class="time">2017-2-15</span>
                    <span class="floor">1#</span>
                </div>
                <div class="content">
                    <p>1222222222222222222222</p>
                    <span>&nbsp;</span>
                    <span class="comment"><i class="fa fa-comment"></i></span>
                    <span class="report">举报</span>
                </div>
                <div class="actions">
                    <span class="agree"><i class="fa fa-thumbs-o-up"></i><b>5</b></span>
                    <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b>5</b></span>
                </div>
            </div>
            <div class="comment-item">
                <div class="info">
                    <span class="user">admin</span>
                    <span class="time">2017-2-15</span>
                    <span class="floor">1#</span>
                </div>
                <div class="content">
                    <p>1222222222222222222222</p>
                    <span>&nbsp;</span>
                    <span class="comment"><i class="fa fa-comment"></i></span>
                    <span class="report">举报</span>
                </div>
                <div class="actions">
                    <span class="agree"><i class="fa fa-thumbs-o-up"></i><b>5</b></span>
                    <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b>5</b></span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<div class="book-comment-form">
    <div class="title">
        发表评论
    </div>
    <form id="comment-form" method="post" action="<?=$this->url('blog/comment/save')?>">
        <input type="hidden" name="blog_id" value="<?=$blog_id?>">
        <input type="hidden" name="parent_id">
        <?php if (Auth::guest()):?>
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
function getMoreComments(page) {
    $.get('<?=$this->url(['blog/comment/more', 'blog_id' => $blog_id])?>&page=' + page, function (html) {
        if (page < 2) {
            $("#comment-box").html(html);
        } else {
            $("#comment-box").append(html);
        }
    });
}
$(document).ready(function () {
    $(".comment-item .expand").click(function() {
        $(this).parent().parent().toggleClass("active");
    });
    $(".comment-item .comment").click(function() {
        $(this).parent().append($(".book-comment-form"));
        $(".book-comment-form .title").text("回复评论");
        $(".book-comment-form .btn-submit").text("回复");
    });
    $(".book-comment-form .btn-cancel").click(function() {
        $(".hot-comments").after($(".book-comment-form"));
        $(".book-comment-form .title").text("发表评论");
        $(".book-comment-form .btn-submit").text("评论");
    });
    $("#comment-form").submit(function () {
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            if (data.code == 200) {
                window.location.reload();
                return;
            }
            alert(data.message);
        }, 'json');
        return false;
    });
    var page = 1;
    getMoreComments(page);
});
</script>