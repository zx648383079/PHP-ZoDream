<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = sprintf('%s-%s', $chapter->title, $book->name);
?>
<div class="container main-box">
    <?= $this->node('ad-sense', ['code' => 'book_chapter']) ?>
    <div class="reader-container">
        <div class="title-bar">
            <h2><?= $chapter['title'] ?></h2>
            <div class="tag-bar">
                <a href="<?=$book->url?>"><?= $book['name'] ?></a>
                <a href="<?= $this->url('./author', ['id' => $book['author_id']]) ?>">作者:<?= $book['author']['name'] ?></a>
                <span>更新时间：<?= $chapter['updated_at'] ?></span>
            </div>
        </div>
        <article class="reader-body">
        <?=$chapter->type < 1 ? $chapter->body->html : ''?>
        </article>
    </div>
    <div class="control-bar">
        <?php if($chapter->previous):?>
        <div class="prev-item">
            <b>（快捷键：←）&#160;</b>
            <a href="<?=$chapter->previous->url?>" title="上一章">上一章</a>
        </div>
        <?php endif;?>
        <div class="menu-item">
            <a href="<?=$book->url?>" title="回目录">回目录</a>
        </div>
        <?php if($chapter->next):?>
        <div class="next-item">
            <a href="<?=$chapter->next->url?>" title="下一章">下一章</a><b>&#160;（快捷键：→）</b>
        </div>
        <?php endif;?>
    </div>

</div>

<div class="reader-sidebar">
    <ul>
        <li>
            <a href="<?=$book->url?>">
                <i class="fa fa-list"></i>
                <span>目录</span>
            </a>
        </li>
        <li class="do-setting">
            <i class="fa fa-cog"></i>
            <span>设置</span>
        </li>
        <li class="go-top">
            <i class="fa fa-arrow-up"></i>
        </li>
    </ul>
</div>

<div class="setting-dialog">
    <div class="dialog-body">
        <ul>
            <li>
                <span>阅读主题</span>
                <div class="theme-box">
                    <span class="theme-0 active"></span>
                    <span class="theme-1"></span>
                    <span class="theme-2"></span>
                    <span class="theme-3"></span>
                    <span class="theme-4"></span>
                    <span class="theme-5"></span>
                    <span class="theme-6"></span>
                </div>
            </li>
            <li>
                <span>正文字体</span>
                <div class="font-box">
                    <span>雅黑</span>
                    <span>宋体</span>
                    <span>楷书</span>
                    <span>启体</span>
                </div>
            </li>
            <li>
                <span>字体大小</span>
                <div class="size-box">
                    <i class="fa fa-minus"></i>
                    <span class="lang">18</span>
                    <i class="fa fa-plus"></i>
                </div>
            </li>
        </ul>
    </div>
    <div class="dialog-footer btn-group">
        <button type="button" class="btn btn-primary dialog-yes">保存</button>
        <button type="button" class="btn btn-secondary dialog-close">取消</button>
    </div>
</div>