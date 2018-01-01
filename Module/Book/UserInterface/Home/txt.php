<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>好看的<? echo str_replace('·','',$typename); ?>小说下载_好看的<? echo str_replace('·','',$typename); ?>小说txt下载_{dede:global.cfg_webname/}</title>
<meta name="keywords" content="<? echo str_replace('·','',$typename); ?>小说下载,<? echo str_replace('·','',$typename); ?>小说txt下载,{dede:global.cfg_webname/}" />
<meta name="description" content="<? echo str_replace('·','',$typename); ?>小说txt下载，最新最好看的<? echo str_replace('·','',$typename); ?>小说下载尽在{dede:global.cfg_webname/}" />
<link rel="stylesheet" type="text/css" href="/css/basic.css" />
<link rel="stylesheet" type="text/css" href="/css/body_inner.css" />
</head><body class="bodyph">
<!--header开始-->
<div class="Layout topbox">
  <div class="topbar">
    <div class="mainbox">
      <div class="left_con">
        <ul>
          <li><a href="{dede:global.cfg_basehost/}/" title="{dede:global.cfg_webname/}">{dede:global.cgf_top_left/}</a></li>
          <li><em class="ver">|</em><a href="{dede:global.cfg_wapurl/}" class="name" style="color:#F00; text-decoration:underline" title="在手机上阅读" target="_blank">手机版</a></li><li><em class="ver">|</em><a href="/over.html" class="name" style="color:#F00;" title="完本小说" target="_blank">完本小说</a></li><li><em class="ver">|</em><a href="/txt.html" class="name" style="color:#F00;" title="小说下载" target="_blank">小说下载</a></li>
        </ul>
      </div>
      <div class="right_con">
        <ul class="UL">
          <li>{dede:global.cgf_top_right/}</li>
          <li><em class="ver">|</em><a href="javascript:" title="加入收藏夹" onclick="addBookmark('{dede:global.cgf_top_left/}','{dede:global.cfg_basehost/}')">收藏本站</a></li>
        </ul>
        <ul class="fUL">
          <li><a href="/" title="返回首页">返回首页</a></li>
			<? echo $channellist1; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="Layout h">
  <div class="header">
    <div class="top">
      <div class="logo"><a href="/" title="{dede:global.cfg_webname/}"><img src="/images/logo.png" alt="{dede:global.cfg_webname/}" /></a></div>
      <div class="c_con">
      </div>
      <div class="s_box">
        <form name="searchform" id="searchform" method="get" target="_blank" action="/plus/search.php">		
			<input type="hidden" name="kwtype" value="0" />
			<input type="hidden" name="searchtype" value="" />
			<input name="q" id="searchword" type="text" maxlength="18" />
			<input type="submit" value="搜索" id="s_btn" />
        </form>
      </div>
    </div>
    <div class="nav">
      <div class="box"> <a href="{dede:global.cfg_basehost/}" class="home" title="{dede:global.cfg_webname/}">{dede:global.cfg_webname/}</a>
		<? echo $channellist2; ?>
		</div>
    </div>
  </div>
</div>
<!--header结束-->
<div class="clear"></div>
<!--body开始-->
<div class="Layout local">当前位置：<a href="{dede:global.cfg_basehost/}">{dede:global.cfg_webname/}</a>&nbsp;>&nbsp;<a href="/txt.html">小说下载</a></div>
<div class="clear"></div>
<div class="Layout m_list list">
  <div class="Head">
    <h2><? echo $typename; ?>小说下载列表</h2><span class="j"></span>
    <div class="morelist">
      <div class="more"><a href="<? echo $overurl; ?>" style="color:#F00;font-weight: 800; text-decoration:underline" title="完本小说下载">完本小说下载&nbsp;>></a></div>
      <ul>
		<li>（共<b><? echo $scount; ?></b>部）</li>&nbsp;
		<li><a href="/txt.html" style="color:#AA0; text-decoration:underline;font-weight: 800" title="全部小说下载">全部小说下载</a></li>&nbsp;>&nbsp;
		<? echo $channellist3; ?>
      </ul>
    </div>
  </div>
  <div class="Con">
    <div class="Left">
      <div class="m_head"> <span class="c">类型</span> <span class="t">书名/章节</span> <span class="w">字数</span> <span class="a">作者</span><span class="z">状态</span></div>
      <ul class="ul_m_list">
		{dede:datalist}
        <li<? ++$i;if($i%2!=1) echo " class='odd'"  ?>>
          <div class="c">[<a href="{dede:field.retypedir /}.html" title="{dede:field.retypename function='str_replace("·","",@me)' /}小说" target="_blank">{dede:field.retypename function='str_replace("·","",@me)' /}</a>]</div>
          <div class="title">
            <div class="t"><a href="/txt{dede:field.typedir /}.html" title="{dede:field.typename /}txt下载" target="_blank">{dede:field.typename /}txt下载</a></div>
            <div class="n">&nbsp;&nbsp;<a href="{dede:field.typedir /}/{dede:field.id function='zhangjie(@me)' /}</a> </div>
          </div>
          <div class="words"><?php echo $fields['booksize']; ?></div>
          <div class="author"><a href="{dede:field.zuozhedir /}.html" title="{dede:field.zuozhe /}作品" target="_blank">{dede:field.zuozhe /}</a></div>
          <div class="abover"><span>{dede:field.overdate function='zhuangtai(@me)' /}</span></div>
        </li>
		{/dede:datalist}
      </ul>
      <div class="bot_more">
        <div class="page_info">每页显示<b>&nbsp;50&nbsp;</b>部，共<b><? echo $scount; ?></b>部</div>
        <div class="page_num">
          <div><a class="info">第<b><? echo $pageno; ?></b>页/共<? echo $topage; ?>页</a><a href="<? echo $pagestart; ?>" title="第一页">第一页</a><a href="<? echo $pagepre; ?>" title="上一页">上一页</a></div><div><a href="<? echo $pagenext; ?>" title="下一页">下一页</a><a href="<? echo $pageend; ?>" title="最后一页">最后一页</a></div>
        </div>
      </div>
    </div>
    <div class="Right">
		<div class="r_box cn">
			<div class="head"><h2>小说作家推荐</h2></div>
			<ul>
				{dede:php}
				global $dsql ;
				$dsql->safeCheck = false;
				$n=1;
				$query = "SELECT COUNT(a.id) AS ano,a.zuozhe AS zuozhe,b.typeimg,b.description,b.typedir,b.tuijian,SUM(a.bookclick) AS bookclick,SUM(a.booksize) AS booksize,SUM(a.tuijian) AS booktuijian FROM dede_arctype b JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `dede_arctype`)-(SELECT MIN(id) FROM `dede_arctype`))+(SELECT MIN(id) FROM `dede_arctype`)) AS id) AS t2 LEFT JOIN dede_arctype a ON(a.zuozhe=b.typename) WHERE b.reid=45 AND b.id>t2.id GROUP BY b.typename order by booktuijian desc limit 10";
				$dsql->SetQuery($query);
				$dsql->Execute();
				while($row=$dsql->GetArray()){
					if($n==1)
					{
						$zuozhe=$row['zuozhe'];
						$newbook = $dsql->GetOne("SELECT typename,typedir FROM dede_arctype where zuozhe='$zuozhe' order by overdate");
						$newbookname = $newbook['typename'];
						$newbooktypeidr = $newbook['typedir'];
						echo "<li><a href='".$row['typedir'].".html' title='".$row['zuozhe']."作品集' target='_blank'>".$row['zuozhe']."</a><span>".$row['ano']."/".$row['booksize']."</span></li><li class='first_con'><div class='pic'><a href='".$row['typedir'].".html' title='".$row['zuozhe']."作品集' target='_blank'><img class='lazy' src='".$row['typeimg']."' alt='".$row['zuozhe']."' /></a></div>
						<div class='a_l'>
						  <div><span>作品数:</span>".$row['ano']."</div>
						  <div><span>总字数:</span>".$row['booksize']."</div>
						  <div><span>总点击:</span>".$row['bookclick']."</div>
						  <div><span>作家推荐:</span>".$row['tuijian']."</div>
						  <div><span>作品推荐:</span>".$row['booktuijian']."</div>
						  <div><span>新书:</span><a href='".$newbooktypeidr."/' title='".$newbookname."' target='_blank'>".$newbookname."</a></div>
						</div>
					</li>";
					}
					else
					{
						echo "<li><a href='".$row['typedir'].".html' title='".$row['zuozhe']."作品集' target='_blank'>".$row['zuozhe']."</a><span>".$row['ano']."/".$row['booksize']."</span></li>";
					}
					$n++;
				}
				{/dede:php}
		
			</ul>
		</div><div class="r_box cmztj cn">
        <div class="head"><h2>热门新书推荐</h2></div>
        <ul>
          {dede:php}
				global $dsql ;
				$n=1;
				$query = "SELECT a.typeimg,a.typename,a.description,a.typedir,a.bookclick,a.tuijian,a.zuozhe,b.typedir as zuozhedir,c.typename as retypename,c.typedir as retypedir FROM dede_arctype a left join dede_arctype b on(b.typename=a.zuozhe and b.reid=45) left join dede_arctype c on(c.id=a.reid) WHERE a.reid<>45 and a.reid<>0 and a.booksize<300000 order by a.bookclick+a.tuijian desc limit 0,10";
				$dsql->SetQuery($query);
				$dsql->Execute();
				while($row=$dsql->GetArray()){
					$bt=$row['bookclick']+$row['tuijian'];
					if($n==1)
					{
						echo "<li><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'>".$row['typename']."</a><span>".$bt."</span></li><li class='first_con'><div class='pic'><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'><img class='lazy' src='".$row['typeimg']."' alt='".$row['typename']."' /></a></div>
						<div class='a_l'>
						  <div><span>作者:</span><a href='".$row['zuozhedir'].".html' title='".$row['zuozhe']."小说作品' target='_blank'>".$row['zuozhe']."</a></div>
						  <div><span>类型:</span><a href='".$row['retypedir'].".html' title='".$row['retypename']."小说' target='_blank'>".$row['retypename']."</a></div>
						  <div><span>点/推:</span>".$row['bookclick']."/".$row['tuijian']."</div>
						</div>
					<div class='info'><p><a href='".$row['typedir']."/' target='_blank'>简介：".cn_substr(html2text($row['description']),50)."……</a></p>
					</div>
				</li>";
					}
					else
					{
						echo "<li><a href='".$row['typedir']."/' title='".$row['retypename']."小说-".$row['typename']."，作者：".$row['zuozhe']."' target='_blank'>".$row['typename']."</a><span>".$bt."</span></li>";
					}
					$n++;
				}
				{/dede:php}
           </ul>
      </div>
      <div class="r_box rmwbtj cn">
        <div class="head">
          <h2>热门完本推荐</h2>
        </div>
        <ul>
        {dede:php}
				global $dsql ;
				$n=1;
				$query = "SELECT a.typeimg,a.typename,a.description,a.typedir,a.bookclick,a.tuijian,a.zuozhe,b.typedir as zuozhedir,c.typename as retypename,c.typedir as retypedir FROM dede_arctype a left join dede_arctype b on(b.typename=a.zuozhe and b.reid=45) left join dede_arctype c on(c.id=a.reid) WHERE a.reid not in(0,45) and a.overdate<>'0' order by a.bookclick+a.tuijian desc limit 0,10";
				$dsql->SetQuery($query);
				$dsql->Execute();
				while($row=$dsql->GetArray()){
					$bt=$row['bookclick']+$row['tuijian'];
					if($n==1)
					{
						echo "<li><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'>".$row['typename']."</a><span>".$bt."</span></li><li class='first_con'><div class='pic'><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'><img class='lazy' src='".$row['typeimg']."' alt='".$row['typename']."' /></a></div>
						<div class='a_l'>
						  <div><span>作者:</span><a href='".$row['zuozhedir'].".html' title='".$row['zuozhe']."小说作品' target='_blank'>".$row['zuozhe']."</a></div>
						  <div><span>类型:</span><a href='".$row['retypedir'].".html' title='".$row['retypename']."小说' target='_blank'>".$row['retypename']."</a></div>
						  <div><span>点/推:</span>".$row['bookclick']."/".$row['tuijian']."</div>
						</div>
					<div class='info'><p><a href='".$row['typedir']."/' target='_blank'>简介：".cn_substr(html2text($row['description']),50)."……</a></p>
					</div>
				</li>";
					}
					else
					{
						echo "<li><a href='".$row['typedir']."/' title='".$row['retypename']."小说-".$row['typename']."，作者：".$row['zuozhe']."' target='_blank'>".$row['typename']."</a><span>".$bt."</span></li>";
					}
					$n++;
				}
				{/dede:php}
		</ul>
      </div>
      <div class="r_box ad ad200"><!--ad-->
      </div>
    </div>
  </div>
</div>
<!--body结束-->
<div class="clear"></div>
<!--footer开始-->
<div class="Layout ft">
  <div class="center">
    <div class="bot_logo"><a href="/" title="新书在线"><img src="/images/bot_logo.png" alt="新书在线" /></a></div>
    <div class="link">
      <div class="z">{dede:flink typeid='1'/}</div>
      <div class="f"><span>版权声明：{dede:global.novel_powerby/}</span></div>
    </div>
  </div>
</div>
<div class="floatBox">
  <ul class="fbox">
    <li class="fli udLI"><a class="fbtn UD">返回顶部</a></li>
  </ul>
</div>
<div class="TIP"></div>
<div class="MAK"></div>
<!--footer结束-->
<div style="display:none;">
  <script type="text/javascript" src="/js/jquery-1.9.0.min.js"></script>
  <script type="text/javascript" src="/js/jquery.lazyload.min.js"></script>
  <script type="text/javascript">
  var cmsUrl="/";
  $("img.lazy").show().lazyload({placeholder:"/images/loading.gif",loading:true,threshold:200,failure_limit:10,skip_invisible:false,effect:"fadeIn"});
  function btnin(){$("#info").css("display","block")};function btnout(){$("#info").css("display","none")};
  window.onscroll=function(){var top = (document.documentElement.scrollTop || document.body.scrollTop);if (top>170){$(".topbox").addClass("topFLOAT");$(".UD").fadeIn();}else{$(".topbox").removeClass("topFLOAT");$(".UD").fadeOut();}};
  $(".UD").click(function(){$("html,body").animate({scrollTop:0});});
  $(".fli").hover(function(){$(this).addClass("on");},function(){$(this).removeClass("on");});
  $(".tab .head a").hover(function(){$(this).siblings().removeClass("active");$(this).addClass("active");$(".tab .box").hide();var showBOX=$(this).attr("showBOX");$("."+showBOX).show();});
  $(".t_btn a").hover(function(){$(".t_btn a").removeClass("active");$(this).addClass("active");var ul_class=$(this).attr("name");$(".r_box.djb ul").css("display","none");$(".r_box.djb ul"+"."+ul_class).css("display","block");});
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
  <script type="text/javascript" src="/js/xmlJS.js"></script>
  {dede:myad name='tongji'/}
</div>
</body></html>