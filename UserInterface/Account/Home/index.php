<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Authentication\Auth;
/** @var $this \Zodream\Domain\Response\View */
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
    <div class="row" id="info">
        <div class="col-md-3">
            <img src="<?=Auth::user()['avatar']?>">
        </div>
        <div class="col-md-9">
            <p><?=Auth::user()['name']?> <button class="btn btn-primary">签到</button></p>
            <p><?=Html::a('查看个人信息', 'info')?></p>
            <p>个性签名</p>
        </div>
    </div>

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

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
