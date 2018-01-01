<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={dede:global.cfg_soft_lang/}" />
<title>{dede:field name='typename'/}新书{dede:field.seotitle /}_{dede:field name='typename'/}最新小说作品_{dede:field name='typename'/}全部小说作品_{dede:global.cfg_webname/}</title>
<meta name="keywords" content="{dede:field name='typename'/}新书,{dede:field name='typename'/}作品,{dede:field name='typename'/}小说,{dede:field name='typename'/}全集,{dede:field name='typename'/}作品集,{dede:field name='typename'/}全部小说,{dede:field name='typename'/}最新作品,{dede:field name='typename'/}最新小说,{dede:field name='typename'/}小说下载" />
<meta name="description" content="{dede:field name='typename'/}的新书{dede:field.typeid runphp='yes'"}
		   global $dsql ;
		   $rows = $dsql->GetOne("SELECT typename FROM dede_arctype where id=@me");
		   $typename = $rows['typename'];
		   $description2="";
		   $description3="已经更新了，本站提供".$typename."最新小说作品";
		   $description4="";
		   $description5="全文在线阅读以及".$typename."已经完本的小说";
		   $description6="";
		   $description7="txt全集免费下载";
			$query = "select a.typename,a.overdate,b.typename as retypename from `#@__arctype` a left join `#@__arctype` b on(a.reid=b.id) where a.zuozhe='$typename' order by overdate asc";
			$dsql->SetQuery($query);
			$dsql->Execute();
			@me="";
			while($row=$dsql->GetArray()){
				$overdate=$row['overdate'];
				if($overdate=="0" || $overdate=="")
				{
					$description2.=($description2=="") ? "《".$row['typename']."》":"、《".$row['typename']."》";
					$description4.=($description4=="") ? "《".$row['typename']."》":"、《".$row['typename']."》";
				}
				else
					$description6.=($description6=="") ? "《".$row['typename']."》":"、《".$row['typename']."》";
			}
			@me=$description2.$description3.$description4.$description5.$description6.$description7;
		{/dede:field.typeid}，{dede:field name='typename'/}全部小说作品电子书下载，{dede:field name='typename'/}小说全集免费在线阅读，尽在{dede:global.cfg_webname/}" />
<meta name="author" content="{dede:field name='typename'/}">
<meta http-equiv="mobile-agent" content="format=xhtml; url={dede:global.cfg_wapurl/}{dede:field name='typedir'/}.html"/>
<link rel="stylesheet" type="text/css" href="/css/basic.css" />
<link rel="stylesheet" type="text/css" href="/css/body_inner.css" />
</head>
<body>
<!--header开始-->
{dede:include filename="head.htm"/}
<!--header结束--> 

<div class="clear"></div>
<!--body开始-->
<div class="Layout local">当前位置：<a href="{dede:global.cfg_cmsurl/}/" title="{dede:global.cfg_webname/}">{dede:global.cfg_webname/}</a> > <a href="{dede:field name='typedir'/}.html" title="{dede:field.typename/}作品集">{dede:field name='typename' function='str_replace("·","",@me)'/}作品集</a></div>
<div class="clear"></div>
<div class="Layout no_h">
  <div class="Con jj">
    <div class="Left">
      <div class="p_box">
                <div class="pic"><a href="{dede:field name='typedir'/}.html" title="{dede:field.typename/}"><img class="lazy" src="/images/blank.gif" data-original="{dede:field.typeimg/}" alt="{dede:field.typename/}" /></a></div>
        <div class="rmxx_box">
          <h2>热门小说推荐</h2>
          <div class="a_box HOT_BOX">
			{dede:sql sql='select typedir,typename from dede_arctype where reid not in(0,45) order by bookclickw limit 0,15'}
			<a href="[field:typedir/]/" title="[field:typename/]" target="_blank">[field:typename/]</a>
			{/dede:sql}
                      
          </div>
        </div>
      </div>
      <div class="j_box">
        <div class="title">
          <h2><a href="{dede:field name='typedir'/}.html" title="{dede:field.typename/}">{dede:field.typename/}</a></h2>
        </div>
		<div class="info">
			<ul>
			{dede:field.typename runphp='yes'"}
			   global $dsql ;
			   $typename = @me;
				$row = $dsql->GetOne("select count(id) as count,sum(booksize) as booksize,sum(bookclick) as bookclick,sum(bookclickm) as bookclickm,sum(bookclickw) as bookclickw,sum(tuijian) as tuijian,sum(tuijianm) as tuijianm,sum(tuijianw) as tuijianw from `#@__arctype` where zuozhe='$typename'");
				@me="";
				if($row)
				{
				@me.="<li><span>作品数：</span><a>".$row['count']."</a></li>
					<li class='lb'><span>总字数：</span>".$row['booksize']."</li>";
					@me.="<li><span>总点击：</span><font id='cms_clicks'>".$row['bookclick']."</font></li>";
					@me.="<li><span>月点击：</span><font id='cms_mclicks'>".$row['bookclickm']."</font></li>";
					@me.="<li class='zd'><span>周点击：</span><font id='cms_wclicks'>".$row['bookclickw']."</font></li>";
					@me.="<li><span>总推荐：</span><font id='cms_ready_1'>".$row['tuijian']."</font></li>";
					@me.="<li><span>月推荐：</span><font id='cms_favorites'>".$row['tuijianm']."</font></li>";
					@me.="<li class='wj'><span>周推荐：</span>".$row['tuijianw']."</li>";
				}
			{/dede:field.typename}
			</ul>
          <div class="praisesBTN"><a href="javascript:ajax_praise('{dede:field.id /}');" title="推荐作家！"><font id="cms_praises">{dede:field.tuijian /}</font> 推荐作家！</a></div>
        </div>
        <div class="words">
			 <p>简介：<br/><a href="{dede:field name='typedir'/}.html" title="{dede:field.typename/}新书">{dede:field name='typename'/}新书</a>{dede:field.typeid runphp='yes'"}
		   global $dsql ;
		   $rows = $dsql->GetOne("SELECT typename,typedir FROM dede_arctype where id=@me");
		   $typename = $rows['typename'];
		   $typedir = $rows['typedir'];
		   $description2="";
		   $description3="已经更新了，本站提供<a href='".$typedir.".html' title='".$typename."最新小说'>".$typename."最新小说</a>作品";
		   $description4="";
		   $description5="全文在线阅读以及".$typename."已经完本的小说";
		   $description6="";
		   $description7="txt全集免费下载";
			$query = "select a.typename,a.overdate,b.typename as retypename from `#@__arctype` a left join `#@__arctype` b on(a.reid=b.id) where a.zuozhe='$typename' order by overdate asc";
			$dsql->SetQuery($query);
			$dsql->Execute();
			@me="";
			while($row=$dsql->GetArray()){
				$overdate=$row['overdate'];
				if($overdate=="0" || $overdate=="")
				{
					$description2.=($description2=="") ? "《".$row['typename']."》":"、《".$row['typename']."》";
					$description4.=($description4=="") ? "《".$row['typename']."》":"、《".$row['typename']."》";
				}
				else
					$description6.=($description6=="") ? "《".$row['typename']."》":"、《".$row['typename']."》";
			}
			@me=$description2.$description3.$description4.$description5.$description6.$description7;
		{/dede:field.typeid}，<a href="{dede:field name='typedir'/}.html" title="{dede:field.typename/}全部小说">{dede:field name='typename'/}全部小说</a>作品txt电子书免费下载，<a href="{dede:field name='typedir'/}.html" title="{dede:field.typename/}小说">{dede:field name='typename'/}小说</a>全集免费在线阅读，尽在{dede:global.cfg_webname/}。</p>
        </div>
        <div class="read_btn">
          <div class="btn" style="width:328px"><a href="javascript:addBookmark('{dede:field.typename/}新书-{dede:global.cfg_webname/}')" class="sc" title="加入收藏夹" style="margin-right:2px">加入收藏夹</a></div>
        </div>
        <div class="vote"><!--AD-->{dede:myad name='booklist31'/}
        </div>
      </div>
    </div>
    <div class="Right">
      <div class="r_box tab">
        <div class="head"> <a class="l active" showBOX="BOX1">其他作家推荐</a></div>
        <div class="box BOX1" style="display:block;">
          <ul>
		  {dede:field.typeid runphp='yes'"}
			global $dsql ;
			$content="";
			$n=1;
			$query = "SELECT COUNT(a.id) AS ano,a.zuozhe AS zuozhe,b.typeimg,b.description,b.typedir,b.tuijian,SUM(a.bookclick) AS bookclick,SUM(a.booksize) AS booksize,SUM(a.tuijian) AS booktuijian FROM dede_arctype a LEFT JOIN dede_arctype b ON(b.typename=a.zuozhe and b.reid=45) WHERE a.reid<>45 and a.reid<>0 and b.id<>@me GROUP BY a.zuozhe order by tuijian desc,booktuijian desc limit 0,10";
			$dsql->SetQuery($query);
			$dsql->Execute();
			@me="";
			while($row=$dsql->GetArray()){
				if($n==1)
				{
					$zuozhe=$row['zuozhe'];
					$newbook = $dsql->GetOne("SELECT typename,typedir FROM dede_arctype where zuozhe='$zuozhe' order by overdate");
					$newbookname = $newbook['typename'];
					$newbooktypeidr = $newbook['typedir'];
					$content.="<li><a href='".$row['typedir'].".html' title='".$row['zuozhe']."作品集' target='_blank'>".$row['zuozhe']."</a><span>".$row['ano']."/".$row['booksize']."</span></li><li class='first_con'><div class='pic'><a href='".$row['typedir'].".html' title='".$row['zuozhe']."作品集' target='_blank'><img class='lazy' src='".$row['typeimg']."' alt='".$row['zuozhe']."' /></a></div>
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
					$content.="<li><a href='".$row['typedir'].".html' title='".$row['zuozhe']."作品集' target='_blank'>".$row['zuozhe']."</a><span>".$row['ano']."/".$row['booksize']."</span></li>";
				}
				$n++;
			}
			@me=$content;
			{/dede:field.typeid}
		</ul>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clear"></div>
<div class="Layout bw">
  <div class="Head">
    <h2>{dede:field name='typename'/}作品集</h2>
    <span class="j"></span>
  </div>
  <div class="Con">
    <div class="Left">      
		{dede:field.typeid runphp='yes'"}
		   global $dsql ;
		   $rows = $dsql->GetOne("SELECT typename FROM dede_arctype where id=@me");
		   $typename = $rows['typename'];
			$query = "select a.id,a.typename,a.typedir,a.typeimg,a.zuozhe,a.booksize,a.bookclick,a.description,a.startdate,a.overdate,b.typename as retypename,b.typedir as retypedir from `#@__arctype` a left join `#@__arctype` b on(a.reid=b.id) where a.zuozhe='$typename' order by startdate desc";
			$dsql->SetQuery($query);
			$dsql->Execute();
			@me="";
			while($row=$dsql->GetArray()){
				$overdate=$row['overdate'];
				if($overdate=="0" || $overdate=="")	$overdate="连载中";
				else $overdate="已完结";
				@me.="<div class='bw_box'>
                    <div class='t'><A href='".$row['typedir']."/' target='_blank' title='".$row['typename']."在线阅读txt下载'>".$row['typename']."</A><span>（".round($row['booksize']/10000)."万字-".$overdate."）</span></div>
                    <div class='pic'><A href='".$row['typedir']."/' target='_blank' title='".$row['typename']."在线阅读txt下载'><img src='".$row['typeimg']."' alt='".$row['typename']."在线阅读txt下载'/></a></div>
                    <div class='a_l'>
                      <div class='a'><span>作者:</span>".$row['zuozhe']."</div>
                      <div class='l'><span>类型:</span><A href='".$row['retypedir'].".html' target='_blank' title='查看全部".$row['retypename']."小说' >".$row['retypename']."</A></div>
					  <div class='l'><span>下载:</span><A href='/txt".$row['typedir'].".html' target='_blank' title='".$row['typename']."txt下载' >TXT下载</A></div>
                    </div>
                    <div class='info'>
                      <p>简介：".cn_substr(html2text($row['description']),62)." ...</p>
                    </div>
                  </div>";
			}
		{/dede:field.typeid}
    </div>
    <div class="Right">
      <div class="r_box qldzb">
        <div class="head">
          <h2>月度点击榜</h2>
        </div>
        <ul>
			{dede:field.typeid runphp='yes'"}
				global $dsql ;
				$n=1;
				$content="";
				$query = "SELECT a.typeimg,a.typename,a.description,a.typedir,a.bookclickm,a.zuozhe,b.typedir as zuozhedir,c.typename as retypename,c.typedir as retypedir FROM dede_arctype a left join dede_arctype b on(b.typename=a.zuozhe and b.reid=45) left join dede_arctype c on(c.id=a.reid) WHERE a.reid not in(0,45) order by bookclickm desc limit 0,5";
				$dsql->SetQuery($query);
				$dsql->Execute();
				while($row=$dsql->GetArray()){
				$counta=$row['bookclickm'];
					if($n==1)
					{
						$content.="<li><a href='".$row['typedir']."/' title='".$row['typename']."-".$row['zuozhe']."作品' target='_blank'>".$row['typename']."</a><span>".$counta."</span></li><li class='first_con'><div class='pic'><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'><img class='lazy' src='".$row['typeimg']."' alt='".$row['typename']."' /></a></div><div class='a_l'><div class='a'><span>作者:</span><a href='".$row['zuozhedir'].".html' target='_blank' title='".$row['zuozhe']."作品'>".$row['zuozhe']."</a></div><div class='l'><span>类型:</span>[<A href='".$row['retypedir'].".html' target='_blank' title='".str_replace("·","",$row['retypename'])."小说' >".str_replace("·","",$row['retypename'])."</A>]</div><div class='l'><span>下载:</span><a href='/txt".$row['typedir'].".html' target='_blank' title='".$row['typename']."txt下载'>txt下载</a></div></div><div class='info'><p><a href='".$row['typedir']."/' target='_blank'>简介：".cn_substr(html2text($row['description']),50)."……</a></p></div></li>";
					}
					else
					{
						$content.="<li><a href='".$row['typedir']."/' title='".$row['typename']."-".$row['zuozhe']."作品' target='_blank'>".$row['typename']."</a><span>".$counta."</span></li>";
					}
					$n++;
				}
				@me=$content;
				{/dede:field.typeid}
		</ul>
      </div>
    </div>
  </div>
<div align="left">
<br/><h3>阅读提示：</h3><br/>
您现在浏览的是{dede:field name='typename'/}的小说作品集，如果在阅读的过程中发现我们的转载有问题，请及时与我们联系！<br/>特别提醒的是：小说作品一般都是根据作者写作当时的思考方式虚拟出来的，其情节虚构的成份比较多，切勿当真或模仿！ 
</div>
</div>
<div align="center"><!-- ad --></div>

<!--body结束-->
<div class="clear"></div>
<!--footer开始-->
{dede:include filename="footer2.htm"/}
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
			  alert("360、火狐等浏览器不支持自动添加收藏夹标签。请您使用快捷键 Ctrl+D 进行添加。");  
		  }
	}
}
  </script>
  <script type="text/javascript" src="/js/xmlJS.js"></script>
</div>
{dede:myad name='booklist11'/}
{dede:myad name='booklist21'/}
</body>
</html>