<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
use Zodream\Infrastructure\ObjectExpand\HtmlExpand;
/** @var $this \Zodream\Domain\View\View */

$this->title = $title;
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>

    <div class="banner">
        <div class="container-fluid">
            <section class="slider">
                <div class="flexslider">
                    <ul class="slides">
                        <?php foreach ($banners as $item):?>
                        <li>
                            <div class="banner-top">
                                <h2><?=$item['title']?></h2>
                                <p><?=HtmlExpand::shortString($item['content'], 300)?></p>
                                <div class="bnr-btn">
                                    <a href="<?=Url::to(['blog/view', 'id' => $item['id']])?>" class="hvr-shutter-out-horizontal">阅读更多</a>
                                </div>
                            </div>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </section>
        </div>
    </div>

    <div class="container-fluid provide">
        <div class="row">
            <div class="col-md-3 provide-1">
                <h3>ZoDream</h3>
                <p>PHP 框架，起源于 “做梦” ,汲取其他开源代码！服务大众，永久开源！</p>
            </div>

            <div class="col-md-3 provide-2">
                <h3>WPF ZoDream</h3>
                <p>为框架的工具集，使用 C# 编写，主要使用 WPF、UWP 开发，主要使用 MVVMLIGHT 作为框架，所有软件永久开源。</p>
            </div>

            <div class="col-md-3 provide-3">
                <h3>GO ZoDream</h3>
                <p>目前有基于 beego 的网页 及 pholcus 的爬虫规则 </p>
            </div>

            <div class="col-md-3 provide-4">
                <h3>HTML ZoDream</h3>
                <p>包括部分界面，JS框架, TS 代码， HTML5 游戏</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="container blog">
        <div class="row">
            <div class="col-md-6">
                <h3>博客</h3>
                <p>包括学习经验，及相关教程。</p>
                <a href="<?=Url::to(['blog'])?>" class="btn-show">查看</a>
            </div>
            <div class="col-md-6">
                <h3>文档</h3>
                <p>完善中。。。</p>
                <a href="<?=Url::to(['document'])?>" class="btn-show">阅读</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="panel panel-default">
                      <div class="panel-heading">
                            <h3 class="panel-title">热门动态</h3>
                      </div>
                      <div class="panel-body">
                            <div class="list-group">
                                <?php foreach ($hots as $item):?>
                                <a href="<?=Url::to(['blog/view', 'id' => $item['id']])?>" class="list-group-item">
                                    <h4 class="list-group-item-heading"><?=$item['title']?></h4>
                                    <p class="list-group-item-text"><?=$item['description']?></p>
                                </a>
                                <?php endforeach;?>
                            </div>
                      </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                      <div class="panel-heading">
                            <h3 class="panel-title">最新动态</h3>
                      </div>
                      <div class="panel-body">
                            <ul class="list-group">
                                <?php foreach ($news as $item):?>
                                    <li class="list-group-item">
                                        <a href="<?=Url::to(['blog/view', 'id' => $item['id']])?>">
                                            <?=$item['title']?></a>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                      </div>
                </div>
            </div>
        </div>
    </div>
    
<?php $this->extend('layout/footer')?>