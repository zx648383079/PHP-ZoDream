<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    )), 'zodream/main.css'
);
?>

<nav class="navbar navbar-inverse" role="navigation">
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
        <ul class="nav navbar-nav topMenu">
            <li title="系统设置" data="system"><a href="javascript:0;">系统</a></li>
            <li title="信息管理" data="index"><a href="javascript:0;">信息</a></li>
            <li title="栏目管理" data="column"><a href="javascript:0;">栏目</a></li>
            <li title="微信管理" data="wechat"><a href="javascript:0;">微信</a></li>
            <li title="用户和会员" data="user"><a href="javascript:0;">用户</a></li>
            <li title="模型管理" data="content"><a href="javascript:0;">模型</a></li>
            <li title="商城系统管理" data="shop"><a href="javascript:0;">商城</a></li>
            <li title="其他管理" data="other"><a href="javascript:0;">其他</a></li>
            <li title="扩展菜单项" data="extend"><a href="javascript:0;">扩展</a></li>
            <li title="常用操作" data=""><a href="javascript:0;">常用</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php $this->ech('name');?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php $this->url('account/logout');?>">退出</a></li>
                </ul>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>

<nav class="secondMenu">
    <ul>
        <li data="easy">增加信息</li>
        <li>管理信息</li>
        <li>审核信息</li>
        <li>签发信息</li>
        <li>管理评论</li>
        <li>更新碎片</li>
        <li>更新专题</li>
        <li>数据更新</li>
        <li>数据统计</li>
        <li>排行统计</li>
        <li data="admin.php/main">后台首页</li>
        <li data="<?php $this->url('index.php');?>" target="blank">网站首页</li>
        <li data="map">后台地图</li>
        <li>版本更新</li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <div  id="leftMenu" title="左侧菜单" class="col-md-2">
            <?php $this->extend('Menu/index');?>
        </div>
        <div class="col-md-10">
            <iframe id="main" src="<?php $this->url('main');?>" title="主体"></iframe>
        </div>
    </div>
</div>
</div>


<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>