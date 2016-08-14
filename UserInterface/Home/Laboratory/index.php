<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Url\Url;
/** @var $this \Zodream\Domain\View\View */

$this->title = $title;
$this->extend([
    'layout/head',
    'layout/navbar'
]);
?>

<div class="container">
    <div class="row">
        <h1>此为实验室，所有功能处于试验阶段，不建议正式发布！</h1>
    </div>
    <div class="row lab">
        <div class="col-md-3">
            <div>
                <a href="<?=Url::to('navigation');?>">导航</a>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <a href="<?=Url::to('forum');?>">论坛</a>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <a href="<?=Url::to('chat');?>">聊天室</a>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <a href="<?=Url::to('waste');?>">废料科普</a>
            </div>
        </div>

        <div class="col-md-3">
            <div>
                <a href="<?=Url::to('question');?>">问答</a>
            </div>
        </div>

        <div class="col-md-3">
            <div>
                <a href="<?=Url::to('company');?>">公司供求</a>
            </div>
        </div>
    </div>
</div>

<?php $this->extend('layout/foot')?>