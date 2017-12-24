<?php
use Domain\Model\Advertisement\AdModel;
use Zodream\Service\Routing\Url;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Support\Html;
use Zodream\Html\Tree;
/** @var $this Zodream\Template\View */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$this->title?>-<?=$this->get('tagline')?></title>
    <meta name="Keywords" content="<?=$this->keywords?>" />
    <meta name="Description" content="<?=$this->description?>" />
    <meta name="author" content="<?=$this->author?>" />
    <link rel="icon" href="/assets/images/favicon.png">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/zodream.css">
    <?=$this->header()?>
</head>
<body>
<!--- 顶部广告 ----->
<div class="ad">
    <?=AdModel::getAd(1)?>
</div>
<!--- 顶部 ----->
<div class="header">
    <div class="top">
        <div>
            上海
        </div>
        <div class="user">
            <?php if (Auth::guest()):?>
                <a href="<?=Url::to(['user/login'])?>">登录</a> 或
                <a href="<?=Url::to(['user/register'])?>">注册</a>
            <?php else:?>
                欢迎您，<a href="<?=Url::to(['user'])?>"><?=Auth::user()->name?></a>
                [<a href="<?=Url::to(['user/logout'])?>">退出</a>]
            <?php endif;?>
        </div>
        <div class="help">
            <a class="<?=Url::to(['cart'])?>">我的购物车</a>
            <a class="<?=Url::to([''])?>">个人中心</a>
            <a class="<?=Url::to(['map'])?>">网站地图</a>
            <a class="<?=Url::to(['help'])?>">帮助中心</a>
        </div>
    </div>
    <div class="middle">
        <div class="logo">
            <a href="<?=Url::to(['/'])?>"><img src="<?=$this->logo?>"></a>
        </div>
        <div class="search">
            <select name="kind">
                <option value="0">综合</option>
                <option value="0">商品</option>
                <option value="0">店铺</option>
            </select>
            <input type="text" name="keyword">
            <button >搜索</button>
        </div>
        <div class="cart">
            <a class="<?=Url::to(['cart'])?>">我的购物车(<?=$this->cartCount?>)</a>
        </div>
    </div>
    <div class="bottom">
        <div class="cats">
            <div>全部分类</div>
            <?=Tree::make($this->cats)?>
        </div>
        <ul class="topMenu">
            <li><a href="<?=Url::to(['/'])?>">首页</a></li>
            <?php foreach ($this->toMenu as $item):?>
                <li <?php if ($item['active']):?>class="active"<?php endif;?>>
                    <?=Html::a($item['name'], $item['url'])?></li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
