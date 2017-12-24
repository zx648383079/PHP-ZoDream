<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Domain\Access\Auth;
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */
$this->registerCssFile('zodream/account.css');
$this->extend([
    'layout/header',
    'layout/navbar'
]);
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
                        <?=$this->ago($item['create_at']);?>
                    </li>
                <?php endforeach;?>
            </ul>

            <?php $page->pageLink();?>
        </div>
    </div>
</div>

<?php $this->extend('layout/footer')?>
