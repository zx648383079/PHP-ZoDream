<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@pc.min.css')->registerJsFile('@jquery.min.js');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$this->title?></title>
    <meta name="keywords" content="<?=$this->keywords?>">
    <meta name="description" content="<?=$this->description?>">
    <?= $this->header() ?>
</head>
<body class="body44 article article2">
<script type="text/javascript">if(getcookie('cgColor')!=""){var cgColor=getcookie('cgColor');$("body").css('background-color',cgColor);}</script>
<!--header开始-->
<?php $this->extend('./headarc1') ?>
<!--header结束-->
<div class="clear"></div>
<div class="Layout local">当前位置：<a href="<?=$this->url('./')?>" title="">新书在线-世间唯有读书高</a> >
    <a href="<?=$cat->url?>"><?=$cat->real_name?>小说</a> >
    <a href="<?=$book->url?>"><?=$book->name?> 最新章节</a></div>
<div class="clear"></div>
<!--body开始-->
<div class="Layout no_bg no_bor">
  <div class="Con box_con">
    <h2><?=$chapter->title?></h2>
    <div class="info"><a href="<?=$book->url?>" title="<?=$book->name?>"><?=$book->name?></a>
        &#160;|&#160;作者:<?=$book->author->name?>&#160;|&#160;更新时间：<?=$chapter->created_at?></div>
    <div class="box_head"></div>
	<div align="center" style="font-size: 12px; padding-bottom: 20px;">推荐阅读:
        <?php foreach ($like_book as $key => $item):?>
            <?= $key > 0 ? '、' : ''?>
            <a href="<?=$item->wap_url?>"><?=$item->name?></a>
        <?php endforeach;?>
    </div>
    <div class="box_box">
        <?=$chapter->body->content; ?>
    </div>
    <div class="box_head bot"></div>
	
	<div align="center" style="font-size:13px"><strong><a href="<?=$book->url?>" title="<?=$book->name?>最新章节"><?=$book->name?>最新章节</a>
            <?=$this->url()?>，欢迎<a href="javascript:" title="收藏<?=$book->name?>">收藏</a>！</strong></div>
    <div class="btn_box">
      <div class="u"> <span class="pre"><b>（快捷键：←）&#160;</b><a href="<?=$chapter->prev->url?>" id="keyleft" title="上一章">上一章</a></span> <span class="bookhome">
              <a id="keyenter" href="<?=$book->url?>" title="回目录">回目录</a></span> <span class="next">
              <a href="<?=$chapter->next->url?>" id="keyright" title="下一章">下一章</a><b>&#160;（快捷键：→）</b></span> </div>
      <div class="d"> <a href="<?=$this->url('./')?>" title="返回首页">返回首页</a>&#160;&#166;&#160;<a href="<?=$book->url?>" title="返回目录">返回目录</a>&#160;&#166;&#160;
          <a href="" title="加入书签">加入书签</a>
      </div>
    </div>
	<div align="center" style="font-size: 12px; padding-bottom: 20px;">新书推荐:
        <?php foreach ($new_book as $key => $item):?>
            <?= $key > 0 ? '、' : ''?>
            <a href="<?=$item->wap_url?>"><?=$item->name?></a>
        <?php endforeach;?>
    </div>
  </div>
</div>
<!--body结束-->
<div class="clear"></div>
<!--footer开始-->
<?php $this->extend('./footer3')?>
<?=$this->footer()?>
</body>
</html>
