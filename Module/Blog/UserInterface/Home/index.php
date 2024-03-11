<?php
defined('APP_DIR') or exit();

use Module\SEO\Domain\Option;
use Zodream\Template\View;
use Domain\Repositories\LocalizeRepository;
/** @var $this View */
$sort_list = ['recommend' => __('Best'), 'new' => __('New'), 'hot' => __('Hot')];
$tags = [__('blog')];
$data = [
    'keywords' => __('site keywords'),
    'description' => __('site description')
];
if (!empty($term)) {
    $tags[] = $term->name;
    $data['description'] = $term->description;
    if (!empty($term->keywords)) {
        $data['keywords'] = $term->keywords;
    }
}
if (!empty($tag)) {
    $tags[] = $tag;
    $data['keywords'] = $tag;
}
if (!empty($programming_language)) {
    $tags[] = $programming_language;
    $data['keywords'] = $programming_language;
}
if (!empty($sort) && isset($sort_list[$sort])) {
    $tags[] = $sort_list[$sort];
}
$listView = Option::value('blog_list_view', 0);
$this->title = implode('|', array_reverse($tags));
$js = <<<JS
bindBlogPage();
JS;
$this->set($data)->extend('layouts/header')->registerJs($js, View::JQUERY_READY);
?>

<div class="book-title">
    <ul class="book-nav">
        <li class="book-navicon">
            <i class="fa fa-bars"></i>
        </li>
        <li class="active">
            <a href="<?=$this->url('./')?>"><?=__('blog')?></a></li>
        <li class="book-search">
            <form>
                <input type="text" name="keywords" value="<?=$this->text($keywords)?>">
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
                <i class="fa fa-bookmark"></i><a href="<?=$item->url?>" title="<?=LocalizeRepository::formatValueWidthPrefix($item, 'name')?>"><?=LocalizeRepository::formatValueWidthPrefix($item, 'name')?></a>
                <?php if($item['blog_count'] > 0):?>
                <span class="count"><?=$item['blog_count'] > 99 ? '99+' : $item['blog_count']?></span>
                <?php endif;?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="book-new">
        <h3><?=__('Latest Blog')?></h3>
        <ul>
            <?php foreach ($new_list as $item): ?>
                <li>
                    <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=$this->text($item->title)?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php if(!empty($comment_list)):?>
    <div class="book-dynamic">
        <h3><?=__('Latest Comment')?></h3>
        <?php foreach ($comment_list as $item): ?>
        <dl>
            <dt><a href="<?=$item->blog->url?>#comments"><?=$this->text($item->user_name)?></a> <?=__('Commented')?> 《<a href="<?=$item->blog->url?>"><?=$this->text($item->blog->title)?></a>》</dt>
            <dd>
                <p><?=$this->text($item->content)?></p>
                <span class="book-time"><?=$item->created_at?></span>
            </dd>
        </dl>
        <?php endforeach;?>
    </div>
    <?php endif;?>
</div>

<div class="book-body">
    <div class="book-sort">
        <a href="<?=$this->url('./tag')?>"><?=__('Tags')?></a>
        <a href="<?=$this->url('./archives')?>"><?=__('Archives')?></a>
        <?php foreach ($sort_list as $key => $item):?>
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
            <img src="<?=$term->thumb?>" alt="<?=LocalizeRepository::formatValueWidthPrefix($term, 'name')?>">
            <h3><?=LocalizeRepository::formatValueWidthPrefix($term, 'name')?></h3>
        </div>
        <div class="term-desc"><?=$term->description?></div>
    </div>
    <?php elseif (!empty($tag)):?>
    <h2 class="book-header"><?=$this->text(__($tag))?></h2>
    <?php elseif (!empty($programming_language)):?>
    <h2 class="book-header"><?=$this->text($programming_language)?></h2>
    <?php endif;?>
    <?php foreach ($blog_list as $item):?>
    <div class="book-item">
        <?php if($listView === 1):?>
        <div class="item-cover">
            <img src="<?=$item->thumb?>" alt="<?=$this->text($item->title)?>">
        </div>
        <?php endif;?>
        <dl class="item-body">
            <dt>
                <?php if($item->open_type > 0):?>
                    <i class="fa fa-lock" title="<?=__('Reading needs to meet the conditions')?>"></i>
                <?php endif;?>
                <a href="<?=$item->url?>" title="<?=$this->text($item->title)?>"><?=$this->text($item->title)?></a>
                <span class="book-time"><?=$this->ago($item->getAttributeSource('created_at'))?></span></dt>
            <dd>
                <p><?=$this->text($item->description)?></p>
                <a class="author" href="<?=$this->url('./', ['user' => $item->user_id])?>"><i class="fa fa-edit"></i><b><?=$this->text($item->user->name)?></b></a>
                <?php if($item->term):?>
                <a class="category" href="<?=$this->url('./', ['category' => $item->term_id])?>"><i class="fa fa-bookmark"></i><b><?=LocalizeRepository::formatValueWidthPrefix($item['term'], 'name')?></b></a>
                <?php endif;?>
                <?php if(!empty($item->programming_language)):?>
                <a class="language" href="<?=$this->url('./', ['programming_language' => $item->programming_language])?>"><i class="fa fa-code"></i><b><?=$item->programming_language?></b></a>
                <?php endif;?>
                <span class="comment"><i class="fa fa-comments"></i><b><?=$item->comment_count?></b></span>
                <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$item->recommend_count?></b></span>
                <span class="click"><i class="fa fa-eye"></i><b><?=$item->click_count?></b></span>
            </dd>
        </dl>
        <?php if($listView === 2):?>
        <div class="item-cover">
            <img src="<?=$item->thumb?>" alt="<?=$this->text($item->title)?>">
        </div>
        <?php endif;?>
    </div>
    
    <?php endforeach;?>
</div>

<div class="book-footer">
    <?=$blog_list->getLink([
        'template' => '<ul class="book-pager">{list}</ul>',
        'active' => '<li class="active">{text}</li>',
        'common' => '<li><a href="{url}">{text}</a></li>'
    ])?>
    <div class="book-clear"></div>
</div>
<?= $this->node('ad-sense', ['code' => 'blog_list']) ?>
    
<?php $this->extend('layouts/footer');?>