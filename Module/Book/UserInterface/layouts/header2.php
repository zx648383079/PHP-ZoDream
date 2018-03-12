<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@book.css');
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
   <body class="<?=$this->body_class?>">
   <div class="Layout topbox active topFLOAT">
  <div class="topline"></div>
  <div class="topbtn">
    <div class="open" onclick="setcookie('topbar_control',1)">打开</div>
    <div class="close" onclick="setcookie('topbar_control',0)">关闭</div>
  </div>
  <div class="topbar"> 
    <script type="text/javascript">if(getcookie("topbar_control")=="0"){$(".topbar").hide();$(".topbox").removeClass("active");}</script>
    <div class="mainbox">
      <div class="left_con"> 
        <ul>
          <li><?=$site_name?></li>
          <li><em class="ver">|</em><a href="<?=$this->url('./mobile')?>" class="name" style="color:#F00; text-decoration:underline" title="在手机上阅读" target="_blank">手机版</a></li>
            <li><em class="ver">|</em><a href="<?=$this->url('./search/list', ['status' => 2])?>" class="name" style="color:#F00;" title="完本小说" target="_blank">完本小说</a></li>
            <li><em class="ver">|</em><a href="<?=$this->url('./search/download')?>" class="name" style="color:#F00;" title="小说下载" target="_blank">小说下载</a></li>
        </ul>
      </div>
      <div class="right_con">
        <ul>
            <li><a href="<?=$this->url('./')?>" title="返回首页">返回首页</a></li>
            <?php foreach ($cat_list as $key => $item):?>
                <li><em class="ver">|</em><a href="<?=$item->url?>" title="<?=$item->real_name?>小说"><?=$item->real_name?></a></li>
            <?php endforeach;?>
            <li><em class="ver">|</em><a href="<?=$this->url('./search/top')?>" title="小说排行榜小说">小说排行榜</a></li>
            <li><em class="ver">|</em><a href="<?=$this->url('./search/list')?>" title="小说书库小说">小说书库</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="topblank"></div>
<script type="text/javascript">if(getcookie("topbar_control")=="0"){$(".topblank").hide();}</script> 