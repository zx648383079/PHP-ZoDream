<?php
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的博客';
$url = $this->url('./home/suggest');
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
    var form = $(".book-search");
    if (form.hasClass('focus')) {
        $(".book-search form").submit();
        return;
    }
    form.addClass("focus");
});
$(".book-navicon").click(function() {
    $('.book-skin').toggleClass("book-collapsed");
});
$(".book-search [name=keywords]").keypress(function() {
    var keywords = $(this).val();
    if (!keywords) {
        return;
    }
    $.getJSON('{$url}?keywords=' + keywords, function(data) {
      if (data.code != 200) {
          return;
      }
      var html = '';
      $.each(data.data, function(i, item) {
        html += '<li>'+item+'</li>'
      });
      $(".book-search .search-tip").html(html);
    });
});
$(".book-search .search-tip").on('click', 'li', function() {
  $(".book-search [name=keywords]").val($(this).text());
  $(".book-search form").submit();
});

JS;
$data = [];
if (!empty($term)) {
    $data = [
        'keywords' => $term->keywords,
        'description' => $term->description,
    ];
}
$this->extend('layouts/header', $data)->registerJs($js, View::JQUERY_READY);
?>

    <div class="book-title">
        <ul class="book-nav">
            <li class="book-navicon">
                <i class="fa fa-navicon"></i>
            </li>
            <li>
                <a href="<?=$this->url('/')?>">首页</a>
            </li>
            <li class="active">
                <a href="<?=$this->url('/blog')?>">博客</a></li>
            <li>关于</li>
            <li class="book-search">
                <form>
                    <input type="text" name="keywords" value="<?=$keywords?>">
                    <i class="fa fa-search"></i>
                    <ul class="search-tip">
                    </ul>
                </form>
            </li>
        </ul>
    </div>

        <div class="book-sidebar">
        <div class="book-chapter">
            <ul>
                <?php foreach ($cat_list as $item): ?>
                <li <?=$category == $item->id ? 'class="active"' : '' ?>>
                    <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=$item->name?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="book-new">
            <h3>最新文章</h3>
            <ul>
                <?php foreach ($new_list as $item): ?>
                    <li>
                        <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=$item->title?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <?php if(!empty($comment_list)):?>
        <div class="book-dynamic">
            <h3>最新留言</h3>
            <?php foreach ($comment_list as $item): ?>
            <dl>
                <dt><a href="<?=$item->blog->url?>#comments"><?=$item->user_name?></a> 评论了 《<a href="<?=$item->blog->url?>"><?=$item->blog->title?></a>》</dt>
                <dd>
                    <p><?=$item->content?></p>
                    <span class="book-time"><?=$item->created_at?></span>
                </dd>
            </dl>
            <?php endforeach;?>
        </div>
        <?php endif;?>
    </div>
    
    <div class="book-body">
        <div class="book-sort">
            <?php foreach (['recommend' => '推荐', 'new' => '最新', 'hot' => '最热'] as $key => $item):?>
                <?php if ($key == $sort):?>
                    <a class="active" href="<?=$this->url(['sort' => $key])?>"><?=$item?></a>
                <?php else:?>
                    <a href="<?=$this->url(['sort' => $key])?>"><?=$item?></a>
                <?php endif;?>
            <?php endforeach;?>
        </div>
        <?php if (!empty($term)):?>
        <div class="book-term">
            <div class="term-info">
                <img src="<?=$term->thumb?>" alt="<?=$term->name?>">
                <h3><?=$term->name?></h3>
            </div>
            <div class="term-desc"><?=$term->description?></div>
        </div>
        <?php endif;?>
        <?php foreach ($blog_list as $item):?>
        <dl class="book-item">
            <dt><a href="<?=$item->url?>"><?=$item->title?></a>
                <span class="book-time"><?=$item->created_at?></span></dt>
            <dd>
                <p><?=$item->description?></p>
                <span class="author"><i class="fa fa-edit"></i><b><?=$item->user->name?></b></span>
                <span class="category"><i class="fa fa-bookmark"></i><b><?=$item->term->name?></b></span>
                <span class="comment"><i class="fa fa-comments"></i><b><?=$item->comment_count?></b></span>
                <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$item->recommend?></b></span>
                <span class="click"><i class="fa fa-eye"></i><b><?=$item->click_count?></b></span>
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
    
<?php $this->extend('layouts/footer');?>