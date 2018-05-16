<?php
use Zodream\Template\View;
use Zodream\Domain\Access\Auth;
/** @var $this View */
?>

<ul>
    <li><a href="<?=$this->url('./admin')?>">
            <i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-users"></i><span>用户管理</span></a>
        <ul>
                <li><a href="<?=$this->url('./admin/user')?>">
                    <i class="fa fa-list"></i><span>用户列表</span></a></li>
                <li><a href="<?=$this->url('./admin/user/create')?>">
                    <i class="fa fa-plus"></i><span>新增用户</span></a></li>
        </ul>
    </li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-user"></i><span><?=Auth::user()->name?></span></a>
        <ul>
                <li><a href="<?=$this->url('./admin/account')?>">
                    <i class="fa fa-info-circle"></i><span>个人资料</span></a></li>
                <li><a href="<?=$this->url('./admin/account/password')?>">
                    <i class="fa fa-key"></i><span>更改密码</span></a></li>
                <li><a href="<?=$this->url('./logout')?>">
                    <i class="fa fa-sign-out"></i><span>退出登陆</span></a></li>
        </ul>
    </li>
</ul>
