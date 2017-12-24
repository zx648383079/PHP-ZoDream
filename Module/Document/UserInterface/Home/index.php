<?php
use Zodream\Template\View;
/** @var $this View */

$this->extend('layout/header');
?>


<div class="container">
    <div class="left-catelog navbar">
        <ul>
                <li><a href="<?=$this->url('wx/manage')?>"><i class="fa fa-home"></i><span>首页</span></a></li>
                <li class="expand"><a href="javascript:;"><i class="fa fa-briefcase"></i><span>消息管理</span></a>
                    <ul>
                        <li><a href="/admin.php/blog"><i class="fa fa-list"></i><span>关注回复</span></a></li>
                        <li><a href="/admin.php/blog/term"><i class="fa fa-list"></i><span>自动回复</span></a></li>
                        <li><a href="/admin.php/blog/detail"><i class="fa fa-edit"></i><span>关键字回复</span></a></li>
                    </ul>
                </li>
                <li class="expand">
                    <a href="javascript:;"><i class="fa fa-briefcase"></i><span>素材管理</span></a>
                    <ul>
                        <li><a href="/admin.php/blog"><i class="fa fa-list"></i><span>图文消息</span></a></li>
                        <li><a href="/admin.php/blog/term"><i class="fa fa-list"></i><span>图片</span></a></li>
                        <li><a href="/admin.php/blog/detail"><i class="fa fa-edit"></i><span>语音</span></a></li>
                        <li><a href="/admin.php/blog/setting"><i class="fa fa-gear"></i><span>视频</span></a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?=$this->url('wx/menu')?>"><i class="fa fa-briefcase"></i><span>菜单管理</span></a>
                </li>
                <li class="expand">
                    <a href="javascript:;"><i class="fa fa-briefcase"></i><span>用户管理</span></a>
                    <ul>
                        <li><a href="/admin.php/blog"><i class="fa fa-list"></i><span>已关注</span></a></li>
                        <li><a href="/admin.php/blog/term"><i class="fa fa-list"></i><span>黑名单</span></a></li>
                    </ul>
                </li>
                <li class="expand">
                    <a href="javascript:;"><i class="fa fa-briefcase"></i><span>记录管理</span></a>
                    <ul>
                        <li><a href="/admin.php/blog"><i class="fa fa-list"></i><span>全部消息</span></a></li>
                        <li><a href="/admin.php/blog/term"><i class="fa fa-list"></i><span>已收藏的消息</span></a></li>
                    </ul>
                </li>
                <li class="expand"><a href="javascript:;"><i class="fa fa-briefcase"></i><span>公众号管理</span></a>
                    <ul>
                        <li><a href="/admin.php/blog"><i class="fa fa-list"></i><span>所有公众号</span></a></li>
                    </ul>
                </li>
            </ul>
    </div>
    <div class="right-content">

    </div>
</div>




<?php
$this->extend('layout/footer');
?>