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
          <li>新书在线-世间唯有读书高</li>
          <li><em class="ver">|</em><a href="http://test.sogua2008.com/wap.php" class="name" style="color:#F00; text-decoration:underline" title="在手机上阅读" target="_blank">手机版</a></li>
            <li><em class="ver">|</em><a href="/over.html" class="name" style="color:#F00;" title="完本小说" target="_blank">完本小说</a></li>
            <li><em class="ver">|</em><a href="/txt.html" class="name" style="color:#F00;" title="小说下载" target="_blank">小说下载</a></li>
        </ul>
      </div>
      <div class="right_con">
        <ul>
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
<div class="topblank"></div>
<script type="text/javascript">if(getcookie("topbar_control")=="0"){$(".topblank").hide();}</script> 