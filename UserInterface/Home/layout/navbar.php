
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
            <li><a href="<?php $this->url('/');?>">首页</a></li>
            <li<?php $this->cas($this->hasUrl('blog'), ' class="active"');?>><a href="<?php $this->url('blog');?>">博客</a></li>
            <li<?php $this->cas($this->hasUrl('forum'));?>><a href="<?php $this->url('forum');?>">论坛</a></li>
            <li<?php $this->cas($this->hasUrl('talk'));?>><a href="<?php $this->url('talk');?>">日志</a></li>
        </ul>
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="搜索">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div><!-- /.navbar-collapse -->
</nav>
