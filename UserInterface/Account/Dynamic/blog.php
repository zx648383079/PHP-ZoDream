<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'zodream/account.css'
    )
);
$page = $this->gain('page');
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
                    <?=Html::a($item['title'], ['index.php/blog/view', 'id' => $item['id']])?>
                    <?php $this->ago($item['create_at']);?>
                </li>
                <?php endforeach;?>
            </ul>

            <?php $page->pageLink();?>
        </div>
    </div>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
