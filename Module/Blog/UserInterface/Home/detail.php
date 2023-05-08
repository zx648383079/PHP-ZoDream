<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Blog\Domain\Model\BlogModel;
use Zodream\Helpers\Json;
use Infrastructure\HtmlExpand;
use Module\Blog\Domain\CCLicenses;
use Domain\Repositories\LocalizeRepository;
/** @var $this View */
/** @var $blog BlogModel */

function getSEOValue(string $key, array $metaItems, BlogModel $blog) {
    $seoKey = 'seo_'.$key;
    if (!empty($metaItems[$seoKey])) {
        return $metaItems[$seoKey];
    }
    return $blog->{$key};
}


$this->title = $this->text(getSEOValue('title', $metaItems, $blog));
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
    'keywords' => $this->text($blog->keywords),
    'description' => $this->text(getSEOValue('description', $metaItems, $blog)),
    'layout_og' => [
        'type' => 'article',
        'image' => $blog->thumb,
        'release_date' => $blog->updated_at
    ],
])->extend('layouts/header')
    ->registerJsFile('@jquery.sideNav.min.js')
    ->registerJsFile('@clipboard.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>
<div class="book-title book-mobile-inline">
    <h1><?=$this->text($blog->title)?></h1>
</div>

<div class="book-sidebar">
    <div class="book-chapter">
        <ul>
            <?php foreach ($cat_list as $item): ?>
                <li <?=$blog->term_id == $item->id ? 'class="active"' : '' ?>>
                    <i class="fa fa-bookmark"></i><a href="<?=$item->url?>" title="<?=LocalizeRepository::formatValueWidthPrefix($item, 'name')?>"><?=LocalizeRepository::formatValueWidthPrefix($item, 'name')?></a>
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


<div class="book-body book-fork-box<?= $blog->type == 1 && $metaItems['is_hide'] ?  '' : ' open' ?>">
    <a class="book-fork" href="https://github.com/zx648383079/PHP-ZoDream">
        <span>Fork Me On Github</span>
    </a>
    <div class="info">
        <?php if(count($languages) > 1):?>
        <div class="language-toggle">
            <?php foreach($languages as $item):?><?php if($blog->id == $item['id']):?><a href="<?=$this->url('./', ['id' => $item['id']])?>" class="active"><?=$item['name']?></a><?php else:?><a href="<?=$this->url('./', ['id' => $item['id']])?>"><?=$item['name']?></a><?php endif;?><?php endforeach;?>
        </div>
        <?php endif;?>
        <?php if($blog->user):?>
        <a class="author" href="<?=$this->url('./', ['user' => $blog->user_id])?>" title="<?=__('Author')?>"><i class="fa fa-edit"></i><b><?=$this->text($blog->user->name)?></b></a>
        <?php endif;?>
        <?php if($blog->term):?>
        <a class="category" href="<?=$this->url('./', ['category' => $blog->term_id])?>" title="<?=__('Category')?>"><i class="fa fa-bookmark"></i><b><?=LocalizeRepository::formatValueWidthPrefix($blog['term'], 'name')?></b></a>
        <?php endif;?>
        <?php if(!empty($blog->programming_language)):?>
        <a class="language" href="<?=$this->url('./', ['programming_language' => $blog->programming_language], false)?>" title="<?=__('Programming Language')?>"><i class="fa fa-code"></i><b><?=$blog->programming_language?></b></a>
        <?php endif;?>
        <span class="time" title="<?=__('Publish Date')?>"><i class="fa fa-calendar-check"></i><b><?=$this->ago($blog->getAttributeSource('created_at'))?></b></span>
        <?php if($blog->type == 1):?>
        <span class="type">
            <a href="<?=HtmlExpand::toUrl($metaItems['source_url'])?>" title="<?=__('Reprint Tip')?>">
                <i class="fa fa-link"></i><b><?=__('Reprint')?></b>
            </a>
        </span>
        <?php endif;?>
    </div>
    <article id="content" class="content style-type-<?=$blog->edit_type?>">
        <?php $this->extend('./content');?>
        <p class="book-copyright"><?=__('Reprint please keep the original link:')?>
            <a href="<?=$this->url('./', ['id' => $blog->id])?>" title="<?=$this->text($blog->title)?>"><?=$this->url('./', ['id' => $blog->id])?></a>
        </p>
    </article>
    <div class="toggle-open">
        <?php if($blog->type == 1):?>
        <a href="<?=HtmlExpand::toUrl($metaItems['source_url'])?>" target="_blank" title="<?=__('Reprint Tip')?>"><?=__('Click here to view source')?></a>
        <?php else:?>
        <?=__('Click here to view')?>
        <?php endif;?>
        <i class="fa fa-angle-double-down" title="<?=__('Click here to view')?>"></i>
    </div>
    <div class="book-bottom">
        <?php if($blog->type == 1 || !empty($metaItems['cc_license'])):?>
        <div class="book-source">
            <?php if(!empty($metaItems['source_author'])):?>
            <p>
                <span><?=__('Source author:')?></span>
                <strong><?=$this->text($metaItems['source_author'])?></strong>
            </p>
            <?php endif;?>
            <?php if($blog->type == 1):?>
            <p>
                <span><?=__('Reprinted in:')?></span>
                <a href="<?=HtmlExpand::toUrl($metaItems['source_url'])?>" target="_blank" title="<?=__('Reprint Tip')?>">
                    <?=$this->text($metaItems['source_url'])?>
                </a>
            </p>
            <?php endif;?>
            <?php if(!empty($metaItems['cc_license'])):?>
            <p>
                <span><?=__('Copyright:')?></span>
                <span>
                    <?=__('This document is licensed under the "{license}" Creative Commons License.', ['license' => CCLicenses::render($metaItems['cc_license'])])?>
                </span>
            </p>
            <?php endif;?>
            
        </div>
        <?php endif;?>
        <?php if($tags):?>
        <div class="book-tags">
            <span><?=__('Tags:')?></span>
            <?php foreach($tags as $item):?>
                <a href="<?=$this->url('./', ['tag' => $item['name']])?>"><?=__($item['name'])?></a>，
            <?php endforeach;?>
        </div>
        <?php endif;?>
    </div>
    <div class="tools">
        <span class="comment"><i class="fa fa-comments"></i><b><?=$blog->comment_count?></b></span>
        <span class="click"><i class="fa fa-eye"></i><b><?=$blog->click_count?></b></span>
        <span class="agree recommend-blog"><i class="fas fa-thumbs-up"></i><b><?=$blog->recommend_count?></b></span>
    </div>
</div>

<div class="book-navigation">
    <?php if ($blog->previous):?>
    <a class="prev" href="<?=$blog->previous->url?>">
        <i class="fa fa-angle-left"></i>
        <div class="prev-text">
            <span class="prev-label"><?=__('Previous')?></span>
            <span class="prev-title"><?=$this->text($blog->previous->title)?></span>
        </div>
    </a>
    <?php endif;?>
    <?php if ($blog->next):?>
    <a class="next" href="<?=$blog->next->url?>">
        <div class="next-text">
            <span class="next-label"><?=__('Next')?></span>
            <span class="next-title"><?= $this->text($blog->next->title) ?></span>
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
            <div class="list-item">
                <div class="item-title">
                    <a class="name" href="<?=$this->url('./', ['id' => $item->id])?>"><?=$this->text($item->title)?></a>
                    <div class="time"><?=$this->ago($item->getAttributeSource('created_at'))?></div></div>
                <div class="item-meta"><?=$this->text($item->description)?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>


<?php if($metaItems['comment_status'] > 0):?>
<div id="comments" class="book-footer comment">
    
</div>
<?php endif;?>

<?php $this->extend('layouts/footer');?>