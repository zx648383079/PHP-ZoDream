<?php
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Template\View */
?>
<nav class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?=$this->url('/');?>">ZoDream</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li><a href="<?=$this->url('/');?>">首页</a></li>
            <li<?=$this->cas(Url::hasUri('blog'), ' class="active"');?>><a href="<?=$this->url('/blog');?>">博客</a></li>
            <li<?=$this->cas(Url::hasUri('laboratory'));?>><a href="<?=$this->url('/laboratory');?>">实验室</a></li>
            <li<?=$this->cas(Url::hasUri('document'));?>><a href="<?=$this->url('/document');?>">文档</a></li>
            <li<?=$this->cas(Url::hasUri('talk'));?>><a href="<?=$this->url('/talk');?>">日志</a></li>
            <li<?=$this->cas(Url::hasUri('about'));?>><a href="<?=$this->url('/about');?>">关于</a></li>
        </ul>
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" name="search" class="form-control" value="<?=app('request')->get('search')?>" placeholder="搜索">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>

        <ul class="nav navbar-nav navbar-right">
            <?php if (auth()->guest()) :?>
                <li><?=Html::a('登录', ['account.php/auth', 'ReturnUrl' => $this->url()])?></li>
                <li><?=Html::a('注册', ['account.php/auth/register'])?></li>
            <?php else:?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?=auth()->user()['name']?><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><?=Html::a('消息', ['account.php/message'])?></li>
                    <li><?=Html::a('个人中心', ['account.php/info'])?></li>
                    <li><?=Html::a('安全中心', ['account.php/security'])?></li>
                    <li role="separator" class="divider"></li>
                    <li><?=Html::a('登出', ['account.php/auth/logout'])?></li>
                </ul>
            </li>
           <?php endif;?>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>
