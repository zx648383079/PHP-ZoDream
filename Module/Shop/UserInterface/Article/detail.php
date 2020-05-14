<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $article->title;
?>
<div class="article-page">
    <div class="container side-box">
        <ul class="path">
            <li>
                <a href="<?=$this->url('./')?>">Home</a>
            </li>
            <li>
                <?=$category->name?>
            </li>
        </ul>
        <div class="article-box">
            <div>
                <ul class="nav">
                    <li class="active"><?=$article->title?></li>
                </ul>
            </div>
            <div class="main">
                <div class="title"><?=$article->title?></div>
                <div class="content"><?=$article->content?></div>
            </div>
        </div>
    </div>
</div>