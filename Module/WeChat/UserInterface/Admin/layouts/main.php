<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@wechat.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@wechat.min.js'
    ]);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
   <head>
       <meta name="viewport" content="width=device-width, initial-scale=1"/>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="Description" content="<?=$this->description?>" />
       <meta name="keywords" content="<?=$this->keywords?>" />
       <title><?=$this->title?></title>
       <?=$this->header();?>
   </head>
   <body>
   <header>
        <div class="container">
            ZoDream WeChat
        </div>
    </header>
    <div class="container page-box">
        <div class="left-catelog navbar">
            <span class="left-catelog-toggle"></span>
            <ul>
                <li><a href="<?=$this->url('./admin')?>"><i class="fa fa-home"></i><span>首页</span></a></li>
                <li class="expand"><a href="javascript:;"><i class="fa fa-briefcase"></i><span>消息管理</span></a>
                    <ul>
                        <li><a href="<?=$this->url('./admin/reply', ['event' => 'subscribe'])?>"><i class="fa fa-list"></i><span>关注回复</span></a></li>
                        <li><a href="<?=$this->url('./admin/reply', ['event' => 'default'])?>"><i class="fa fa-list"></i><span>自动回复</span></a></li>
                        <li><a href="<?=$this->url('./admin/reply')?>"><i class="fa fa-edit"></i><span>关键字回复</span></a></li>
                    </ul>
                </li>
                <li class="expand">
                    <a href="javascript:;"><i class="fa fa-briefcase"></i><span>素材管理</span></a>
                    <ul>
                        <li><a href="<?=$this->url('./admin/media', ['type' => 'news'])?>"><i class="fa fa-list"></i><span>图文消息</span></a></li>
                        <li><a href="<?=$this->url('./admin/media', ['type' => 'image'])?>"><i class="fa fa-list"></i><span>图片</span></a></li>
                        <li><a href="<?=$this->url('./admin/media', ['type' => 'voice'])?>"><i class="fa fa-edit"></i><span>语音</span></a></li>
                        <li><a href="<?=$this->url('./admin/media', ['type' => 'video'])?>"><i class="fa fa-gear"></i><span>视频</span></a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?=$this->url('./admin/menu')?>"><i class="fa fa-briefcase"></i><span>菜单管理</span></a>
                </li>
                <li class="expand">
                    <a href="javascript:;"><i class="fa fa-briefcase"></i><span>用户管理</span></a>
                    <ul>
                        <li><a href="<?=$this->url('./admin/user')?>"><i class="fa fa-list"></i><span>已关注</span></a></li>
                        <li><a href="<?=$this->url('./admin/user')?>"><i class="fa fa-list"></i><span>黑名单</span></a></li>
                    </ul>
                </li>
                <li class="expand">
                    <a href="javascript:;"><i class="fa fa-briefcase"></i><span>记录管理</span></a>
                    <ul>
                        <li><a href="<?=$this->url('./admin/log')?>"><i class="fa fa-list"></i><span>全部消息</span></a></li>
                        <li><a href="<?=$this->url('./admin/log', ['status' => 'collect'])?>"><i class="fa fa-list"></i><span>已收藏的消息</span></a></li>
                    </ul>
                </li>
                <li class="expand"><a href="javascript:;"><i class="fa fa-briefcase"></i><span>公众号管理</span></a>
                    <ul>
                        <li><a href="<?=$this->url('./admin/manage')?>"><i class="fa fa-list"></i><span>所有公众号</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="right-content">
            <?=$content?>
        </div>
    </div>
   <?=$this->footer()?>
   </body>
</html>