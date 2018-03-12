<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js');
?>
<div class="Layout ft">
  <div class="center">
    <div class="bot_logo"><a href="/" title="<?=$this->site_name?>"><img src="/images/bot_logo.png" alt="<?=$this->site_name?>" /></a></div>
    <div class="link">
      <div class="z"><?=$this->site_name?></div>
      <div class="f"><span>版权声明：<?=$this->site_name?></span></div>
    </div>
  </div>
</div>
<div class="floatBox">
  <ul class="fbox">
    <li class="fli sq"><a href="javascript:addBookmark('{dede:field.typename/}|{dede:field name='zuozhe' function='rtrim(html2text(@me))'/}-<?=$this->site_name?>')" class="fbtn" title="加入书签">加入书签</a></li>            
    <li class="fli ml"><a href="{dede:field name='typedir'/}/" class="fbtn" title="返回目录">返回目录</a></li>
    <li class="fli ml"><a href="/txt{dede:field name='typedir'/}.html" class="fbtn" title="{dede:field.typename/}txt下载">下载本书</a></li>
    <li class="fli wz">
      <ul>
        <li class="s on" cgSize="14px"><a><span>14px</span>小</a></li>
        <li class="m" cgSize="16px"><a><span>16px</span>中</a></li>
        <li class="l" cgSize="20px"><a><span>20px</span>大</a></li>
        <li class="xl" cgSize="24px"><a><span>24px</span>特大</a></li>
      </ul>
      <a class="fbtn">文字大小</a> </li>
    <li class="fli ps">
      <ul>
        <li class="mr on" cgColor="#FBF7F0"><a><span>√</span>默认</a></li>
        <li class="hc" cgColor="#E9FAFF"><a><span>√</span>淡蓝海洋</a></li>
        <li class="yh" cgColor="#FFFFED"><a><span>√</span>明黄清俊</a></li>
        <li class="df" cgColor="#EEFAEE"><a><span>√</span>绿意淡雅</a></li>
        <li class="sl" cgColor="#FCEFFF"><a><span>√</span>红粉世家</a></li>
        <li class="cl" cgColor="#FFFFFF"><a><span>√</span>白雪天地</a></li>
        <li class="hs" cgColor="#EFEFEF"><a><span>√</span>灰色世界</a></li>
      </ul>
      <a class="fbtn">配色</a> </li>
        <li class="fli udLI"><a class="fbtn UD">返回顶部</a></li>
  </ul>
</div>
<div class="TIP"></div>
<div class="MAK"></div>
<!--footer结束-->
<div style="display:none;"> 
  <script type="text/javascript">
  var cmsUrl="<?=$this->url('./')?>";
  document.onkeydown=function(e){var theEvent=window.event || e;var keycode=theEvent.keyCode || theEvent.which;if(keycode=='39'){var url=document.getElementById('keyright').href;location.href=url;}if(keycode=='37'){var url=document.getElementById('keyleft').href;location.href=url;}if(keycode=='13'){var url=document.getElementById('keyenter').href;location.href=url;}};
  function btnin(){$("#info").css("display","block")};function btnout(){$("#info").css("display","none")};
  window.onscroll=function (){var top=(document.documentElement.scrollTop || document.body.scrollTop);if (top>100){$(".UD").fadeIn();}else{$(".UD").fadeOut();}}
  $(".UD").click(function(){$("html,body").animate({scrollTop:0});});
  $(".fli").hover(function(){$(this).addClass("on");},function(){$(this).removeClass("on");});
  $(".topbtn").click(function(){$(".topbtn div").toggle();$(".topbar,.topblank").slideToggle("slow");});
  </script>
  <script type="text/javascript">
  if(getcookie('cgColor')!=""){
	  $(".ps li").removeClass("on");
	  $(".ps li[cgColor="+cgColor+"]").addClass("on");
  }
  if(getcookie('cgSize')!=""){
	  $(".wz li").removeClass("on");
	  $(".wz li[cgSize="+cgSize+"]").addClass("on");
  }
  $(".fli li").click(function(){
	  var cgColor=$(this).attr("cgColor");
	  if(cgColor!=null){
	  $(this).siblings().removeClass("on");$(this).addClass("on");
		  $("body").css('background-color',cgColor);
		  setcookie('cgColor',cgColor);
	  };
	  var cgSize=$(this).attr("cgSize");
	  if(cgSize!=null){
	  $(this).siblings().removeClass("on");$(this).addClass("on");
		  $(".box_box,.box_box p").css('font-size',cgSize);
		  setcookie('cgSize',cgSize);
	  };
  });
  function addBookmark(title,url) {
  if(!title){title =document.title};
  if(!url){url=window.location.href}
  try{  
		  window.external.AddFavorite(url,title);  
	  }catch(e){  
		  try{  
			  window.sidebar.addPanel(title,url,"");  
		  }catch(e){  
			  alert("360、火狐等浏览器不支持自动添加收藏夹标签。关闭本对话框后，请您使用快捷键 Ctrl+D 进行添加。");  
		  }
	}
}
  </script>
</div>
   <?=$this->footer()?>
   </body>
</html>