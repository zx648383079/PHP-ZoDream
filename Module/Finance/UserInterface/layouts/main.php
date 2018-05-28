<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@datetimer.css',
        '@finance.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@finance.min.js'
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
            ZoDream Finance
        </div>
    </header>
    <div class="container page-box">
        <div class="left-catelog navbar">
            <span class="left-catelog-toggle"></span>
            <ul>
                <li><a href="<?=$this->url('./')?>">
                        <i class="fa fa-home"></i><span>首页</span></a></li>
                <li class="expand"><a href="javascript:;">
                        <i class="fa fa-money"></i><span>资金</span></a>
                    <ul>
                        <li><a href="<?=$this->url('./money')?>">
                                <i class="fa fa-list-alt"></i><span>总资本</span></a></li>
                        <li><a href="<?=$this->url('./money/account')?>">
                                <i class="fa fa-credit-card"></i><span>资金账户</span></a></li>
                        <li><a href="<?=$this->url('./money/project')?>">
                                <i class="fa fa-trophy"></i><span>理财项目</span></a></li>
                        <li><a href="<?=$this->url('./money/product')?>">
                                <i class="fa fa-cubes"></i><span>理财产品</span></a></li>
                    </ul>
                </li>
                <li class="expand"><a href="javascript:;">
                        <i class="fa fa-puzzle-piece"></i><span>收支管理</span></a>
                    <ul>
                        <li><a href="<?=$this->url('./income')?>">
                                <i class="fa fa-exchange"></i><span>月收支</span></a></li>
                        <li><a href="<?=$this->url('./income/log')?>">
                                <i class="fa fa-recycle"></i><span>月流水</span></a></li>
                        <li><a href="<?=$this->url('./income/channel')?>">
                                <i class="fa fa-anchor"></i><span>消费渠道</span></a></li>
                    </ul>
                </li>
                <li><a href="<?=$this->url('./budget')?>">
                        <i class="fa fa-tasks"></i><span>生活预算</span></a></li>
            </ul>
        </div>
        <div class="right-content">
            <?=$content?>
        </div>
    </div>
   <?=$this->footer()?>
   </body>
</html>