<?php
use Zodream\Template\View;
use Zodream\Helpers\Str;
/** @var $this View */
$this->title = $blog->title;
$url = (string)$this->url(['./comment', 'blog_id' => $blog->id]);
$recommendUrl = (string)$this->url(['./home/recommend', 'id' => $blog->id]);
$js = <<<JS
    SyntaxHighlighter.all();
    $.get('{$url}', function(html) {
      $(".book-footer").html(html);
    });
    $(".recommend-blog").click(function() {
      var that = $(this).find('b');
      $.getJSON('{$recommendUrl}', function(data) {
        if (data.code == 200) {
            that.text(data.data);
            return;
        }
        Dialog.tip(data.message);
      })
    });
    $(".book-navicon").click(function() {
        $('.book-skin').toggleClass("book-collapsed");
    });
JS;

$this->registerCssFile('ueditor/third-party/SyntaxHighlighter/shCoreDefault.css');
$this->extend('layouts/header')
    ->registerJs($js, View::JQUERY_READY)
    ->registerJsFile('ueditor/third-party/SyntaxHighlighter/shCore.js');
?>
    <div class="book-title book-mobile-inline">
        <ul class="book-nav">
            <li class="book-navicon">
                <i class="fa fa-navicon"></i>
            </li>
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
    <div class="book-chapter">
        <ul>
            <?php foreach ($cat_list as $item): ?>
                <li <?=$blog->term_id == $item->id ? 'class="active"' : '' ?>>
                    <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=$item->name?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="book-body">
        <div class="info">
            <span class="author"><i class="fa fa-edit"></i><b><?=$blog->user_name?></b></span>
            <span class="category"><i class="fa fa-bookmark"></i><b><?=$blog->term_name?></b></span>
            <span class="time"><i class="fa fa-calendar-check-o"></i><b><?=$blog->created_at?></b></span>
        </div>
        <div class="content">
            <?=$blog->content?>
        </div>
        <div class="tools">
            <span class="comment"><i class="fa fa-comments"></i><b><?=$blog->comment_count?></b></span>
            <span class="click"><i class="fa fa-eye"></i><b><?=$blog->click_count?></b></span>
            <span class="agree recommend-blog"><i class="fa fa-thumbs-o-up"></i><b><?=$blog->recommend?></b></span>
        </div>
    </div>
    <div class="book-footer comment">
        
    </div>
    <div class="book-dynamic">
        <?php foreach ($log_list as $log): ?>
            <dl>
                <dt><a><?=$log['name']?></a> <?=$log['action']?>了 《<a href="<?=$this->url('./home/detail/id/'.$log['blog_id'])?>"><?=$log['title']?></a>》</dt>
                <dd>
                    <p><?=$log['content']?></p>
                    <span class="book-time"><?=$this->ago($log['create_at'])?></span>
                </dd>
            </dl>
        <?php endforeach;?>
    </div>

    <?php $this->extend('layouts/footer');?>