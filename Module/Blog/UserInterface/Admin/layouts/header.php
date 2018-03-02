<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@prism.css')
    ->registerCssFile('@zodream.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@blog_admin.css');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?=$this->title?>-博客管理</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>
<body>
<header>
    <div class="container">
        ZoDream 博客管理平台
    </div>
</header>
<div class="container">
    <div class="left-catelog navbar">
        <ul>
            <li><a href="<?=$this->url('./admin')?>"><i class="fa fa-home"></i><span>首页</span></a></li>
            <li class="expand"><a href="javascript:;">
                    <i class="fa fa-book"></i><span>文章管理</span></a>
                <ul>
                    <li><a href="<?=$this->url('./admin/blog')?>">
                            <i class="fa fa-list"></i><span>文章列表</span></a></li>
                    <li><a href="<?=$this->url('./admin/blog/create')?>">
                            <i class="fa fa-edit"></i><span>发表文章</span></a></li>
                </ul>
            </li>
            <li class="expand"><a href="javascript:;">
                    <i class="fa fa-tags"></i><span>分类管理</span></a>
                <ul>
                    <li><a href="<?=$this->url('./admin/term')?>"><i class="fa fa-list"></i><span>分类列表</span></a></li>
                    <li><a href="<?=$this->url('./admin/term/create')?>"><i class="fa fa-edit"></i><span>新增分类</span></a></li>
                </ul>
            </li>
            <li><a href="<?=$this->url('./admin/comment')?>"><i class="fa fa-comment"></i><span>评论</span></a></li>
        </ul>
    </div>
    <div class="right-content">