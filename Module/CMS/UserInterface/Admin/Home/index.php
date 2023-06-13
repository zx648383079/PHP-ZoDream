<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream CMS Admin';
?>

<div class="row">
    <div class="col-md-4">
        <div class="column-full-item">
            <div class="overlay">
                <i class="fa fa-retweet"></i>
            </div>
            <div class="inner">
                <h3><?= intval($data['site_today']) ?>/<?= intval($data['site_count']) ?></h3>
                <p>站点今日新增/总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-folder"></i>
            </div>
            <a class="column-footer" href="<?= $this->url('./admin/site') ?>">
                查看更多
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="column-full-item">
            <div class="overlay">
                <i class="fa fa-retweet"></i>
            </div>
            <div class="inner">
                <h3><?= intval($data['article_today']) ?>/<?= intval($data['article_count']) ?></h3>
                <p>文章今日新增/总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-newspaper"></i>
            </div>
            <a class="column-footer" href="<?= $this->url('./admin/site') ?>">
                查看更多
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="column-full-item">
            <div class="overlay">
                <i class="fa fa-retweet"></i>
            </div>
            <div class="inner">
                <h3><?= intval($data['form_today']) ?>/<?= intval($data['form_count']) ?></h3>
                <p>表单今日新增/总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar"></i>
            </div>
            <a class="column-footer" href="<?= $this->url('./admin/site') ?>">
                查看更多
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="column-full-item">
            <div class="overlay">
                <i class="fa fa-retweet"></i>
            </div>
            <div class="inner">
                <h3><?= intval($data['view_today']) ?>/<?= intval($data['view_count']) ?></h3>
                <p>浏览量今日新增/总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-eye"></i>
            </div>
            <a class="column-footer" href="<?= $this->url('./admin/site') ?>">
                查看更多
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="column-full-item">
            <div class="overlay">
                <i class="fa fa-retweet"></i>
            </div>
            <div class="inner">
                <h3><?= intval($data['comment_today']) ?>/<?= intval($data['comment_count']) ?></h3>
                <p>评论今日/总记录</p>
            </div>
            <div class="icon">
                <i class="fa fa-comments"></i>
            </div>
            <a class="column-footer" href="<?= $this->url('./admin/site') ?>">
                查看更多
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>