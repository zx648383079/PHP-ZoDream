<?php
use Zodream\Domain\View\View;
/** @var $this View */
$js = <<<JS
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
JS;

$this->extend('layout/header')->registerJs($js, View::JQUERY_READY);
?>
    <div class="book-title">
        <ul class="book-nav">
            <li class="book-back"><a href="<?=$this->url('blog')?>">返回</a></li>
            <?php if ($blog->previous):?>
            <li><a href="<?=$blog->previous->url?>"><?=$blog->previous->title?></a></li>
            <?php endif;?>
            <li class="active"><?=$blog->title?></li>
            <?php if ($blog->next):?>
            <li><a href="<?=$blog->next->url?>"><?=$blog->next->title?></a></li>
            <?php endif;?>
        </ul>
    </div>
    <div class="book-body">
        <div class="info">
            <span class="author"><i class="fa fa-edit"></i><b><?=$blog->user_name?></b></span>
            <span class="category"><i class="fa fa-bookmark"></i><b><?=$blog->term_name?></b></span>
            <span class="time"><i class="fa fa-calendar-check-o"></i><b><?=$blog->create_at?></b></span>
        </div>
        <div class="content">
            <?=$blog->content?>
        </div>
        <div class="tools">
            <span class="comment"><i class="fa fa-comments"></i><b><?=$blog->comment_count?></b></span>
            <span class="agree"><i class="fa fa-thumbs-o-up"></i><b><?=$blog->recommend?></b></span>
        </div>
    </div>
    <div class="book-footer comment">
        <div class="book-comments hot-comments">
            <div class="title">
                热门评论
            </div>
            <div class="comment-item">
                <div class="info">
                    <span class="user">admin</span>
                    <span class="time">2017-2-15</span>
                    <span class="floor">4楼</span>
                </div>
                <div class="content">
                    <p>1222222222222222222222</p>
                    <span class="expand">展开（8）</span>
                    <span>&nbsp;</span>
                    <span class="comment"><i class="fa fa-comment"></i></span>
                    <span class="report">举报</span>
                </div>
                <div class="actions">
                    <span class="agree"><i class="fa fa-thumbs-o-up"></i><b>5</b></span>
                    <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b>5</b></span>

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
        </div>
        <div class="book-comment-form">
            <div class="title">
                发表评论
            </div>
            <input type="hidden">
            <div class="form-table">
                <div class="form-group">
                    <label>姓名</label>
                    <input type="text" placeholder="请输入姓名">
                </div>
                <div class="form-group">
                    <label>邮箱</label>
                    <input type="email" placeholder="请输入邮箱">
                </div>
                <div class="form-group">
                    <label>网址</label>
                    <input type="url" placeholder="请输入网址">
                </div>
            </div>
            <textarea placeholder="请输入评论内容"></textarea>
            <button class="btn-submit">评论</button>
            <button class="btn-cancel">取消</button>
        </div>
        <div class="book-comments">
            <div class="title">
                全部评论
                <div class="order">
                    <span class="active">最新</span>
                    <span>最早</span>
                </div>
            </div>
            <div class="comment-item">
                <div class="info">
                    <span class="user">admin</span>
                    <span class="time">2017-2-15</span>
                    <span class="floor">4楼</span>
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
        </div>
    </div>
    <div class="book-chapter">
        <ul>
            <?php foreach ($cat_list as $item): ?>
                <li <?=$blog->term_id == $item->id ? 'class="active"' : '' ?>>
                    <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=$item->name?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="book-dynamic">
        <?php foreach ($log_list as $log): ?>
            <dl>
                <dt><a><?=$log['name']?></a> <?=$log['action']?>了 《<a href="<?=$this->url('blog/home/detail/id/'.$log['blog_id'])?>"><?=$log['title']?></a>》</dt>
                <dd>
                    <p><?=$log['content']?></p>
                    <span class="book-time"><?=$this->ago($log['create_at'])?></span>
                </dd>
            </dl>
        <?php endforeach;?>
    </div>

<?php
$this->extend('layout/footer');
?>