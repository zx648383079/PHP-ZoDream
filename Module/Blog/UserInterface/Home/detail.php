<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Infrastructure\HtmlExpand;
/** @var $this View */
$this->title = $blog->title;
$url = $this->url('./', false);
$js = <<<JS
bindBlog('{$url}', '{$blog->id}', {$blog->edit_type});
JS;

if ($blog->edit_type < 1) {
    $this->registerCssFile('ueditor/third-party/SyntaxHighlighter/shCoreDefault.css')
        ->registerJsFile('ueditor/ueditor.parse.min.js')
        ->registerJsFile('ueditor/third-party/SyntaxHighlighter/shCore.js');
}
$this->extend('layouts/header', [
        'keywords' => $blog->keywords,
        'description' => $blog->description,
    ])
    ->registerJsFile('@jquery.sideNav.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>
<div class="book-title book-mobile-inline">
    <ul class="book-nav">
        <li class="book-navicon">
            <i class="fa fa-bars"></i>
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

<div class="book-sidebar">
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
                <dt><a><?=$log['name']?></a> <?=$log['action']?>了 《<a href="<?=$this->url('./detail/id/'.$log['blog_id'])?>"><?=$log['title']?></a>》</dt>
                <dd>
                    <p><?=$log['content']?></p>
                    <span class="book-time"><?=$this->ago($log['create_at'])?></span>
                </dd>
            </dl>
        <?php endforeach;?>
    </div>

    <div class="book-side-nav">
    </div>
</div>


<div class="book-body">
    <a class="book-fork" href="https://github.com/zx648383079/PHP-ZoDream">
        <img src="/assets/images/forkme.png" alt="Fork Me On Github">
    </a>
    <div class="info">
        <?php if($blog->user):?>
        <a class="author" href="<?=$this->url('./', ['user' => $blog->user_id])?>"><i class="fa fa-edit"></i><b><?=$blog->user->name?></b></a>
        <?php endif;?>
        <?php if($blog->term):?>
        <a class="category" href="<?=$this->url('./', ['category' => $blog->term_id])?>"><i class="fa fa-bookmark"></i><b><?=$blog->term->name?></b></a>
        <?php endif;?>
        <?php if(!empty($blog->language)):?>
        <a class="language" href="<?=$this->url('./', ['language' => $blog->language], false)?>"><i class="fa fa-code"></i><b><?=$blog->language?></b></a>
        <?php endif;?>
        <span class="time"><i class="fa fa-calendar-check"></i><b><?=$blog->created_at?></b></span>
        <?php if($blog->type == 1):?>
        <span class="type">
            <a href="<?=HtmlExpand::toUrl($blog->source_url)?>">
                <i class="fa fa-link"></i><b>转载</b>
            </a>
        </span>
        <?php endif;?>
    </div>
    <div id="content" class="content style-type-<?=$blog->edit_type?>">
        <?=HtmlExpand::toHtml($blog->content, $blog->edit_type == 1)?>
    </div>
    <div class="tools">
        <span class="comment"><i class="fa fa-comments"></i><b><?=$blog->comment_count?></b></span>
        <span class="click"><i class="fa fa-eye"></i><b><?=$blog->click_count?></b></span>
        <span class="agree recommend-blog"><i class="fas fa-thumbs-up"></i><b><?=$blog->recommend?></b></span>
    </div>
</div>

<?php if($blog->comment_status > 0):?>
<div id="comments" class="book-footer comment">
    
</div>
<?php endif;?>

<?php $this->extend('layouts/footer');?>