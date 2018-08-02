<?php
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Support\Html;
use Zodream\Infrastructure\Http\URL;
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
        <a class="navbar-brand" href="<?=URL::to('/index.php');?>">ZoDream</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li><a href="<?=URL::to('/index.php');?>">首页</a></li>
            <li><a href="<?=URL::to(['/']);?>">个人中心</a></li>
            <li><a href="<?=URL::to(['task/all']);?>">任务大厅</a></li>
        </ul>
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="搜索">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=auth()->user()['name']?><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><?=Html::a('消息', ['message'])?></li>
                    <li><?=Html::a('个人中心', ['info'])?></li>
                    <li><?=Html::a('安全中心', ['security'])?></li>
                    <li role="separator" class="divider"></li>
                    <li><?=Html::a('登出', ['auth/logout'])?></li>
                </ul>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>
