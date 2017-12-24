<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */

$this->title = $title;
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="term list-group">
                <li class="list-group-item <?=empty($termId) ? 'active' : null?>">
                    <a href="<?=Url::to('blog');?>">全部</a></li>
                <?php foreach ($term as $item) :?>
                    <li class="list-group-item <?=$item['id'] == $termId ? 'active' : null?>">
                        <a href="<?=Url::to(['termid' => $item['id']]);?>"><?=$item['name'];?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div id="list" class="col-md-10">
            <ul class="list">
                <?php foreach ($page->getPage() as $item) :?>
                    <li class="list-item">
                        <div class="recommend" data="<?=$item['id'];?>">
                            <span><?=$item['recommend']?></span>
                            <span>推荐</span>
                        </div>
                        <h4 class="list-item-head">
                            <a href="<?=Url::to(['blog/view', 'id' => $item['id']]);?>">
                                <?=$item['title'];?>
                            </a>
                        </h4>
                        <div class="list-item-content">
                            <?=$item['description'];?>
                        </div>
                        <div class="list-item-foot">
                            <a href="<?=Url::to(['blog', 'user' => $item['user_id']]);?>">
                                <?=$item['user'];?></a>
                             发表于 
                            <span>
                                <?=$this->ago($item['create_at']);?>
                            </span>
                            分类：
                            <span>
                                <?=$item['term'];?>
                            </span>
                            评论（
                             <span>
                                 <?=$item['comment_count'];?>
                             </span>
                            ）
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
            <div>
                <?php $page->pageLink();?>
            </div>
        </div>
    </div>

</div>

<?php $this->extend('layout/footer')?>