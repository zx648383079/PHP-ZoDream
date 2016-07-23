<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'flexslider.css',
        'zodream/home.css'
    )
);
?>

    <div class="banner">
        <div class="container-fluid">
            <section class="slider">
                <div class="flexslider">
                    <ul class="slides">
                        <?php foreach ($this->gain('data', array()) as $item):?>
                        <li>
                            <div class="banner-top">
                                <h2><?=$item['title']?></h2>
                                <p><?=\Infrastructure\HtmlExpand::shortString($item['content'], 300)?></p>
                                <div class="bnr-btn">
                                    <a href="<?php $this->url(['blog/view', 'id' => $item['id']])?>" class="hvr-shutter-out-horizontal">阅读更多</a>
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
                <a href="<?php $this->url(['blog'])?>" class="btn-show">查看</a>
            </div>
            <div class="col-md-6">
                <h3>文档</h3>
                <p>完善中。。。</p>
                <a href="<?php $this->url(['document'])?>" class="btn-show">阅读</a>
            </div>
        </div>
    </div>

<div class="task">
    <div class="container">
        <div class="row">
            <div class="col-md-3 provide-1">
                <span class="glyphicon glyphicon-music"></span>
                <h3>休闲</h3>
                <p>必要的休息只为更好的工作。娱乐环节待开发。。。</p>
            </div>

            <div class="col-md-3 provide-2">
                <span class="glyphicon glyphicon-bell"></span>
                <h3>规律</h3>
                <p>万物皆有规律，有规律的生活有助于工作。</p>
            </div>

            <div class="col-md-3 provide-3">
                <span class="glyphicon glyphicon-user"></span>
                <h3>人</h3>
                <p>以人为本！</p>
            </div>

            <div class="col-md-3 provide-4">
                <span class="glyphicon glyphicon-thumbs-up"></span>
                <h3>鼓励</h3>
                <p>有效鼓励是必要的！</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="container pics">
    <ul>
        <li>
            <a href="#">
                <div class="txt">
                    <p class="p1"></p>
                    <p class="p2">坚持</p>
                </div>
            </a>
        </li>
        <li>
            <a href="#">
                <div class="txt">
                    <p class="p1"></p>
                    <p class="p2">坚持</p>
                </div>
            </a>
        </li>
        <li>
            <a href="#">
                <div class="txt">
                    <p class="p1"></p>
                    <p class="p2">坚持</p>
                </div>
            </a>
        </li>
        <li>
            <a href="#">
                <div class="txt">
                    <p class="p1"></p>
                    <p class="p2">坚持</p>
                </div>
            </a>
        </li>
    </ul>
</div>
<!--
    <div class="fixed-slide">
        <ul>
            <li><a href="#"><div class="box"><img src="<?php $this->asset('images/icon/icon01.png');?>">客服中心</div></a></li>
            <li><a href="#"><div class="box"><img src="<?php $this->asset('images/icon/icon02.png');?>">客户案例</div></a></li>
            <li><a href="#"><div class="box"><img src="<?php $this->asset('images/icon/icon04.png');?>">QQ客服</div></a></li>
            <li><a href="#"><div class="box"><img src="<?php $this->asset('images/icon/icon03.png');?>">新浪微博</div></a></li>
            <li><a href="javascript:;" class="toTop"><div class="box"><img src="<?php $this->asset('images/icon/icon05.png');?>">返回顶部</div></a></li>
        </ul>
    </div>
-->
<?php
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        '!js require(["home/home"]);'
    )
);
?>