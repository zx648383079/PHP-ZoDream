<div class="Layout topbox">
  <div class="topbar">
    <div class="mainbox">
      <div class="left_con">
        <ul>
          <li><a href="<?=$this->url('./')?>" title=""><?=$site_name?></a></li>
          <li><em class="ver">|</em><a href="<?=$this->url('./wap')?>" class="name" style="color:#F00; text-decoration:underline" title="在手机上阅读" target="_blank">手机版</a></li>
            <li><em class="ver">|</em><a href="<?=$this->url('./home/list', ['status' => 2])?>" class="name" style="color:#F00;" title="完本小说" target="_blank">完本小说</a></li>
            <li><em class="ver">|</em><a href="<?=$this->url('./home/download')?>" class="name" style="color:#F00;" title="小说下载" target="_blank">小说下载</a></li>
        </ul>
      </div>
      <div class="right_con">
        <ul class="UL">
            <li>本站所有小说，均为<font class="red"> 全文免费在线阅读 </font>！</li>
          <li><em class="ver">|</em><a href="javascript:" title="加入收藏夹" onclick="addBookmark('<?=$site_name?>','<?=$this->url('./')?>')">收藏本站</a></li>
        </ul>
        <ul class="fUL">
          <li><a href="<?=$this->url('./')?>" title="返回首页">返回首页</a></li>
            <?php foreach ($cat_list as $key => $item):?>
                <li><em class="ver">|</em><a href="<?=$item->url?>" title="<?=$item->real_name?>小说"><?=$item->real_name?></a></li>
            <?php endforeach;?>
            <li><em class="ver">|</em><a href="<?=$this->url('./home/top')?>" title="小说排行榜小说">小说排行榜</a></li>
            <li><em class="ver">|</em><a href="<?=$this->url('./home/list')?>" title="小说书库小说">小说书库</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="Layout h">
  <div class="header">
    <div class="top">
      <div class="logo"><a href="<?=$this->url('./')?>" title="新书小说网-搜刮好东西"><img src="/assets/images/logo.png" alt="新书小说网-搜刮好东西" /></a></div>
      <div class="c_con">
      </div>
      <div class="s_box">
        <div class="s_entry"></div>
        <form name="searchform" id="searchform" method="get" target="_blank" action="<?=$this->url('./home/search')?>">
			<input name="keywords" id="searchword" type="text" maxlength="18" />
			<input type="submit" value="搜索" id="s_btn" />
        </form>
        <div class="hotword">
			<h3>热搜：</h3>          
			<?php foreach ($hot_keywords as $item):?>
            <a href="<?=$this->url('./home/search', ['keywords' => $item])?>"><?=$item?></a>
            <?php endforeach;?>
         </div>
      </div>
    </div>
    <div class="nav">
      <div class="box">
	  <a href="<?=$this->url('./')?>" class="home" title="新书小说网-搜刮好东西">新书小说网-搜刮好东西</a>
          <?php foreach ($cat_list as $key => $item):?>
             <a <?= isset($nav_index) && $nav_index == $item->id ? 'class="ph"' : '' ?> href="<?=$item->url?>" title="<?=$item->real_name?>小说"><?=$item->real_name?></a>
          <?php endforeach;?>
          <a <?= isset($nav_index) && $nav_index == 98 ? 'class="ph"' : '' ?> href="<?=$this->url('./home/top')?>" title="小说排行榜小说">小说排行榜</a>
          <a <?= isset($nav_index) && $nav_index == 99 ? 'class="ph"' : '' ?> href="<?=$this->url('./home/list')?>" title="小说书库小说">小说书库</a>
		</div>
    </div>
  </div>
</div>