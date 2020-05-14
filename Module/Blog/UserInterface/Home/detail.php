<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Blog\Domain\Model\BlogModel;
use Zodream\Helpers\Json;
use Infrastructure\HtmlExpand;
/** @var $this View */
/** @var $blog BlogModel */
$this->title = $blog->title;
$lang = [
    'side_title' => __('Catalog'),
    'reply_btn' => __('Reply'),
    'reply_title' => __('Reply Comment'),
    'comment_btn' => __('Comment'),
    'comment_title' => __('Leave A Comment')
];
$lang = Json::encode($lang);
$js = <<<JS
bindBlog('{$blog->id}', {$blog->edit_type}, {$lang});
JS;

if ($blog->edit_type < 1) {
    $this->registerCssFile('ueditor/third-party/SyntaxHighlighter/shCoreDefault.css')
        ->registerJsFile('ueditor/ueditor.parse.min.js')
        ->registerJsFile('ueditor/third-party/SyntaxHighlighter/shCore.js');
}
$this->set([
    'keywords' => $blog->keywords,
    'description' => $blog->description,
    'layout_og' => [
        'type' => 'article',
        'image' => $blog->thumb,
        'release_date' => $blog->updated_at
    ],
])->extend('layouts/header')
    ->registerJsFile('@jquery.sideNav.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>
<div class="book-title book-mobile-inline">
    <ul class="book-nav">
        <li class="book-navicon">
            <i class="fa fa-bars"></i>
        </li>
        <li class="book-back"><a href="<?=$this->url('blog')?>"><?=__('Back')?></a></li>
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
                    <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=__($item->name)?></a>
                    <?php if($item['blog_count'] > 0):?>
                    <span class="count"><?=$item['blog_count'] > 99 ? '99+' : $item['blog_count']?></span>
                    <?php endif;?>
                    </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="book-dynamic">

    </div>

    <div class="book-side-nav">
    </div>
</div>


<div class="book-body open">
    <a class="book-fork" href="https://github.com/zx648383079/PHP-ZoDream">
        <img src="/assets/images/forkme.png" alt="Fork Me On Github">
    </a>
    <div class="info">
        <?php if(count($languages) > 1):?>
        <div class="language-toggle">
            <?php foreach($languages as $item):?>
            <?php if($blog->id == $item['id']):?>
            <a href="<?=$this->url('./', ['id' => $item['id']])?>" class="active"><?=$this->t($item['language'])?></a>
            <?php else:?>
            <a href="<?=$this->url('./', ['id' => $item['id']])?>"><?=$this->t($item['language'])?></a>
            <?php endif;?>  
            <?php endforeach;?>
        </div>
        <?php endif;?>
        <?php if($blog->user):?>
        <a class="author" href="<?=$this->url('./', ['user' => $blog->user_id])?>"><i class="fa fa-edit"></i><b><?=$blog->user->name?></b></a>
        <?php endif;?>
        <?php if($blog->term):?>
        <a class="category" href="<?=$this->url('./', ['category' => $blog->term_id])?>"><i class="fa fa-bookmark"></i><b><?=__($blog->term->name)?></b></a>
        <?php endif;?>
        <?php if(!empty($blog->programming_language)):?>
        <a class="language" href="<?=$this->url('./', ['programming_language' => $blog->programming_language], false)?>"><i class="fa fa-code"></i><b><?=$blog->programming_language?></b></a>
        <?php endif;?>
        <span class="time"><i class="fa fa-calendar-check"></i><b><?=$blog->created_at?></b></span>
        <?php if($blog->type == 1):?>
        <span class="type">
            <a href="<?=HtmlExpand::toUrl($blog->source_url)?>" title="<?=__('Reprint Tip')?>">
                <i class="fa fa-link"></i><b><?=__('Reprint')?></b>
            </a>
        </span>
        <?php endif;?>
    </div>
    <div id="content" class="content style-type-<?=$blog->edit_type?>">
        <?php $this->extend('./content');?>
    </div>
    <div class="book-bottom">
        <?php if($blog->type == 1):?>
        <div class="book-source">
            <span>转载于：</span>
            <a href="<?=HtmlExpand::toUrl($blog->source_url)?>" target="_blank" title="<?=__('Reprint Tip')?>">
                <?=$this->text($blog->source_url)?>
            </a>
        </div>
        <?php endif;?>
        <?php if($tags):?>
        <div class="book-tags">
            <span>标签：</span>
            <?php foreach($tags as $item):?>
                <a href="<?=$this->url('./', ['tag' => $item['name']])?>"><?=$item['name']?></a>，
            <?php endforeach;?>
        </div>
        <?php endif;?>
    </div>
    <div class="toggle-open">
        <?=__('Click here to view')?> <i class="fa fa-angle-double-down"></i>
    </div>
    <div class="tools">
        <span class="comment"><i class="fa fa-comments"></i><b><?=$blog->comment_count?></b></span>
        <span class="click"><i class="fa fa-eye"></i><b><?=$blog->click_count?></b></span>
        <span class="agree recommend-blog"><i class="fas fa-thumbs-up"></i><b><?=$blog->recommend?></b></span>
    </div>
</div>

<div class="book-navigation">
    <?php if ($blog->previous):?>
    <a class="prev" href="<?=$blog->previous->url?>">
        <i class="fa fa-angle-left"></i>
        <div class="prev-text">
            <span class="prev-label">上一篇</span>
            <span class="prev-title"><?=$blog->previous->title?></span>
        </div>
    </a>
    <?php endif;?>
    <?php if ($blog->next):?>
    <a class="next" href="<?=$blog->next->url?>">
        <div class="next-text">
            <span class="next-label">上一篇</span>
            <span class="next-title"><?= $blog->next->title ?></span>
        </div>
        <i class="fa fa-angle-right"></i>
    </a>
    <?php endif;?>
</div>

<?php if($relation_list):?>
<div class="panel">
    <div class="panel-header">
        <a href="<?=$this->url('./blog')?>"><?=__('Related Articles')?></a>
    </div>
    <div class="panel-body">
        <?php foreach($relation_list as $item):?>
            <div class="list-item"><a class="name" href="<?=$this->url('./', ['id' => $item->id])?>"><?=$item->title?></a><div class="time"><?=$item->created_at?></div></div>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>


<?php if($blog->comment_status > 0):?>
<div id="comments" class="book-footer comment">
    
</div>
<?php endif;?>

<?php $this->extend('layouts/footer');?>