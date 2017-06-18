<?php
use Zodream\Domain\View\View;
/** @var $this View */
$js = <<<JS
$(".book-nav").click(function() {
    $(this).toggleClass("hover");
});
$(".book-search").focus(function() {
    $(this).addClass("focus");
}).blur(function() {
    $(this).removeClass("focus");
});
$(".book-search .fa-search").click(function() {
    $(this).parent().addClass("focus");
});
JS;

$this->extend('layout/header')->registerJs($js, View::JQUERY_READY);
?>

    <div class="book-title">
        <ul class="book-nav">
            <li>首页</li>
            <li class="active">博客</li>
            <li>关于</li>
            <li class="book-search">
                <input type="text">
                <i class="fa fa-search"></i>
                <ul class="search-tip">
                    <li>12323123123</li>
                    <li>12323123123</li>
                    <li>12323123123</li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="book-body">
        <div class="book-sort">
            <a href="index.html">推荐</a>
            <a class="active" href="index.html">最新</a>
            <a href="index.html">最热</a>
        </div>
        <?php foreach ($blog_list as $item):?>
        <dl class="book-item">
            <dt><a href="<?=$this->url('blog/home/detail', ['id' => $item['id']])?>"><?=$item['title']?></a>
                <span class="book-time"><?=$item['create_at']?></span></dt>
            <dd>
                <p><?=$item['description']?></p>
                <span class="author"><i class="fa fa-edit"></i><b><?=$item['user_name']?></b></span>
                <span class="category"><i class="fa fa-bookmark"></i><b><?=$item['term_name']?></b></span>
                <span class="comment"><i class="fa fa-comments"></i><b><?=$item['comment_count']?></b></span>
                <span class="agree"><i class="fa fa-thumbs-o-up"></i><b><?=$item['recommend']?></b></span>
            </dd>
        </dl>
        <?php endforeach;?>
    </div>
    <div class="book-footer">
        <?=$blog_list->getLink([
            'template' => '<ul class="book-pager">{list}</ul>',
            'active' => '<li class="active">{text}</li>',
            'common' => '<li><a href="{url}">{text}</a></li>'
        ])?>
        <div class="book-clear">

        </div>
    </div>
    <div class="book-chapter">
        <ul>
            <li class="active"><i class="fa fa-bookmark"></i><a href="index.html">111111</a></li>
            <li><i class="fa fa-bookmark"></i><a href="index.html">111111</a></li>
        </ul>
    </div>
    <div class="book-dynamic">
        <dl>
            <dt><a>admin</a> 发布了 《<a href="view.html">gggggg...</a>》</dt>
            <dd>
                <p>dddddddddddd</p>
                <span class="book-time">刚刚</span>
            </dd>
        </dl>
        <dl>
            <dt><a>admin</a> 评论了 《<a href="view.html">aaaaa</a>》</dt>
            <dd>
                <p>dddddddddddd</p>
                <span class="book-time">5秒前</span>
            </dd>
        </dl>
        <dl>
            <dt><a>admin</a> 赞了 《<a href="view.html">aaaaa</a>》</dt>
            <dd>
                <span class="book-time">2017-2-13</span>
            </dd>
        </dl>
    </div>
<?php
$this->extend('layout/footer');
?>