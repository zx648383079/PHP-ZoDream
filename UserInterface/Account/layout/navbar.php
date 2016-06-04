<?php
use Zodream\Domain\Authentication\Auth;
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\Response\View */
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
        <a class="navbar-brand" href="<?php $this->url('/');?>">ZoDream</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li<?php $this->cas($this->hasUrl('blog'), ' class="active"');?>><a href="<?php $this->url('/');?>">动态</a></li>
            <li<?php $this->cas($this->hasUrl('blog'));?>><a href="<?php $this->url('blog');?>">博客</a></li>
            <li<?php $this->cas($this->hasUrl('laboratory'));?>><a href="<?php $this->url('laboratory');?>">实验室</a></li>
            <li<?php $this->cas($this->hasUrl('talk'));?>><a href="<?php $this->url('talk');?>">日志</a></li>
        </ul>
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="搜索">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=Auth::user()['name']?><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><?=Html::a('消息', ['message'])?></li>
                    <li><?=Html::a('个人中心', ['user/info'])?></li>
                    <li><?=Html::a('安全中心', ['user/security'])?></li>
                    <li role="separator" class="divider"></li>
                    <li><?=Html::a('登出', ['logout'])?></li>
                </ul>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>
