<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->registerCssFile('zodream/account.css');
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>

<div class="container">

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <?=Html::a('动态', 'dynamic')?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('博客', ['dynamic/blog'])?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('论坛', ['dynamic/forum'])?>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <ul class="list-group">
                <?php foreach ($page->getPage() as $item) :?>
                <li class="list-group-item">
                    <?=$item['user_name']?> 回复了我的主题 《<?=$item['title']?>》
                    <?=$item['content']?>
                    <?php $this->ago($item['create_at']);?>
                </li>
                <?php endforeach;?>
            </ul>

            <?php $page->pageLink();?>
        </div>
    </div>
</div>

<?php $this->extend('layout/footer')?>
