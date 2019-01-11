<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js');
?>

<div class="container">
    <div class="forum-group">
        <div class="group-header">
            1111
        </div>
        <div class="group-body">
            <?php foreach(range(1, 10) as $item):?>
            <div class="forum-item">
                <div class="thumb">
                    <img src="<?=$this->asset('images/zd.jpg')?>" alt="">
                </div>
                <div class="info">
                    <div class="name">
                        <a href="<?=$this->url('./forum', ['id' => $item])?>">123123123</a>
                        <span>(1)</span>
                    </div>
                    <div class="count">主题：，帖数：</div>
                    <div class="last-thread">
                        <a href=""></a>
                        5分钟 admin
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
