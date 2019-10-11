<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('blog');
$url = $this->url('./', false);
$js = <<<JS
bindBlogPage('{$url}');
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
                <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=$item->name?></a>
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
                    <i class="fa fa-bookmark"></i><a href="<?=$item->url?>"><?=$item->title?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php if(!empty($comment_list)):?>
    <div class="book-dynamic">
        <h3><?=__('Latest Comment')?></h3>
        <?php foreach ($comment_list as $item): ?>
        <dl>
            <dt><a href="<?=$item->blog->url?>#comments"><?=$item->user_name?></a> <?=__('Commented')?> 《<a href="<?=$item->blog->url?>"><?=$item->blog->title?></a>》</dt>
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
        <?php foreach (['recommend' => 'Best', 'new' => 'New', 'hot' => 'Hot'] as $key => $item):?>
            <?php if ($key == $sort):?>
                <a class="active" href="<?=$this->url(['sort' => $key])?>"><?=__($item)?></a>
            <?php else:?>
                <a href="<?=$this->url(['sort' => $key])?>"><?=__($item)?></a>
            <?php endif;?>
        <?php endforeach;?>
    </div>
    <?php if (!empty($term)):?>
    <div class="book-term">
        <div class="term-info">
            <img src="<?=$term->thumb?>" alt="<?=$term->name?>">
            <h3><?=__($term->name)?></h3>
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
            <a class="author" href="<?=$this->url('./', ['user' => $item->user_id])?>"><i class="fa fa-edit"></i><b><?=$item->user->name?></b></a>
            <?php if($item->term):?>
            <a class="category" href="<?=$this->url('./', ['category' => $item->term_id])?>"><i class="fa fa-bookmark"></i><b><?=$item->term->name?></b></a>
            <?php endif;?>
            <?php if(!empty($item->programming_language)):?>
            <a class="language" href="<?=$this->url('./', ['programming_language' => $item->programming_language])?>"><i class="fa fa-code"></i><b><?=$item->programming_language?></b></a>
            <?php endif;?>
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