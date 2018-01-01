<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={dede:global.cfg_soft_lang/}" />
<title>{dede:field name='typename'/}txt下载_{dede:field name='typename'/}txt全集下载_电子书免费下载_{dede:global.cfg_webname/}</title>
<meta name="keywords" content="{dede:field name='typename'/}下载,{dede:field name='keywords'/}txt下载,{dede:field name='typename'/}txt电子书,{dede:field name='typename'/}txt全集下载,{dede:field name='typename'/}全集下载,{dede:field name='typename'/}在线阅读,{dede:global.cfg_webname/}" />
<meta name="description" content="{dede:field name='zuozhe' function='trim(html2text(@me))'/}的{dede:field.typeid runphp='yes'"}
						global $dsql ;
						$tida=@me;
						$retype = $dsql->GetOne("SELECT b.typedir,b.typename from `dede_arctype` a left join `dede_arctype` b on(a.reid=b.id) WHERE a.id='$tida'");
						@me=str_replace("·","",$retype['typename'])."小说";
						{/dede:field.typeid}作品《{dede:field name='typename'/}》最新章节已经更新，作者在这本作品上倾注了非常多的精力和时间，本站提供{dede:field name='typename'/}txt下载，{dede:field name='typename'/}txt全集下载，{dede:field name='typename'/}电子书下载，{dede:field name='typename'/}txt免费下载尽在{dede:global.cfg_webname/}。" />
<meta name="author" content="{dede:global.cfg_webname/}">
<meta http-equiv="mobile-agent" content="format=xhtml; url={dede:global.cfg_wapurl/}{dede:field name='typedir'/}/"/>
<link rel="stylesheet" type="text/css" href="/css/basic.css" />
<link rel="stylesheet" type="text/css" href="/css/body_inner.css" />
</head>
<body>
{dede:include filename="head.htm"/}
<div class="clear"></div>
<!--body开始-->
<div class="Layout local">当前位置：<a href="{dede:global.cfg_cmsurl/}/" title="{dede:global.cfg_webname/}">{dede:global.cfg_webname/}</a> > {dede:field.typeid runphp='yes'"}
   global $dsql ;
   $reid = $dsql->GetOne("SELECT reid FROM dede_arctype where id=@me");
   $reid = $reid['reid'];
   $retype = $dsql->GetOne("SELECT typedir,typename FROM dede_arctype where id=$reid");
   @me="<a href='".str_replace('{cmspath}','',$retype['typedir'])."/txt.html'>".str_replace("·","",$retype['typename']);
{/dede:field.typeid}小说下载</a> >  <a href='/txt{dede:field name='typedir'/}.html' title="{dede:field name='typename'/}txt下载">{dede:field name='typename'/}txt下载</a></div>
<div class="clear"></div>
<div class="Layout no_h">
  <div class="Con jj">
    <div class="Left">
      <div class="p_box">
                <div class="pic"><a href="{dede:field name='typedir'/}/" title="{dede:field.typename/}小说"><img class="lazy" src="{dede:field.typeimg/}" alt="{dede:field.typename/}小说" /></a></div>
        <div class="rmxx_box">
          <h2>其他热门小说下载</h2>
          <div class="a_box HOT_BOX">
			{dede:sql sql='select typedir,typename from dede_arctype where reid not in(0,45) and id<>~typeid~ order by bookclickw limit 0,15'}
			<a href="/txt[field:typedir/].html" title="[field:typename/]" target="_blank">[field:typename/]</a>
			{/dede:sql}
                      
          </div>
        </div>
      </div>
      <div class="j_box">
        <div class="title">
          <h2>{dede:field.typename/}txt下载</h2>
        </div>
        <div class="info">
          <ul>
            <li><span>作者：</span>{dede:field.zuozhe /}</li>
            <li class="lb"><span>类型：</span>{dede:field.typeid runphp='yes'"}
						global $dsql ;
						$tida=@me;
						$retype = $dsql->GetOne("SELECT b.typedir,b.typename from `dede_arctype` a left join `dede_arctype` b on(a.reid=b.id) WHERE a.id='$tida'");
						@me="[<A href='".$retype['typedir'].".html' target='_blank' title='". str_replace("·","",$retype['typename'])."小说' >".str_replace("·","",$retype['typename'])."</A>]";
						{/dede:field.typeid}</li>
            <li><span>总点击：</span><font id="cms_clicks">{dede:field.bookclick /}</font></li>
            <li><span>月点击：</span><font id="cms_mclicks">{dede:field.bookclickm /}</font></li>
            <li class="zdj"><span>周点击：</span><font id="cms_wclicks">{dede:field.bookclickw /}</font></li>
            <li><span>总字数：</span><font id="cms_ready_1">{dede:field.booksize /}</font></li>
            <li><span>创作日期：</span><font id="cms_favorites">{dede:field.startdate runphp='yes'"}@me=(@me=="")? "待确认":@me;{/dede:field.startdate}</font></li>
            <li class="wj"><span>状态：</span>{dede:field.overdate runphp='yes'"}@me=(@me==0)? "连载中":"已完本";{/dede:field.overdate}</li>
          </ul>
          <div class="praisesBTN"><a href="javascript:ajax_praise('{dede:field.id /}');" title="推荐本书！"><font id="cms_praises">{dede:field.tuijian /}</font> 推荐本书！</a></div>
        </div>
        <div class="words">
            最新章节：{dede:sql sql="Select a.id,a.title,a.pubdate,b.typedir FROM `dede_archives` a LEFT JOIN `dede_arctype` b on(b.id=a.typeid) Where a.typeid=~typeid~ order by id desc limit 0,1"}<a href="[field:typedir/]/[field:id/].html">[field:title/]</a>（[field:pubdate function='date("Y-m-d H:i:s",@me)'/]）{/dede:sql}
			 <p>{dede:field name='zuozhe' function='trim(html2text(@me))'/}的{dede:field.typeid runphp='yes'"}
						global $dsql ;
						$tida=@me;
						$retype = $dsql->GetOne("SELECT b.typedir,b.typename from `dede_arctype` a left join `dede_arctype` b on(a.reid=b.id) WHERE a.id='$tida'");
						@me="<A href='".$retype['typedir'].".html' target='_blank' title='". str_replace("·","",$retype['typename'])."小说' >".str_replace("·","",$retype['typename'])."小说</A>";
						{/dede:field.typeid}作品《<a href='{dede:field name='typedir'/}/' title="{dede:field name='typename'/}">{dede:field name='typename'/}</a>》最新章节已经更新，作者{dede:field.zuozhe /}在这本作品上倾注了非常多的精力和时间，本站提供<a href='/txt{dede:field name='typedir'/}.html' title="{dede:field name='typename'/}txt下载">{dede:field name='typename'/}txt下载</a>，如果您喜欢这本作品，可以在这里免费下载。<br/>声明：<br/>1、请勿用于商业用途，否则后果非常严重，本站无力承担。<br/>
						2、下载后请尽快删除，好公民应该支持正版阅读。</p>
        </div>
        <div class="read_btn">
          <div class="btn" style="width:108px"><a href="{dede:field name='typedir'/}/" class="yd" title="在线阅读" style="margin-right:2px">在线阅读</a></div>
		  <div class="down">
		  txt文件：<a href="/download/download.php?filetype=txt&filename={dede:field.id/}" title="{dede:field.typename/}txt电子书" target="_blank" onclick="_czc.push(['_trackEvent', '小说下载', 'txt', '{dede:field.typename/}','','']);">点击下载({dede:field.downloadurl function='round(filesize("..".str_replace(".zip",".txt",@me))/1048576,1)'/} MB)</a> | zip压缩包：<a href="/download/download.php?filetype=zip&filename={dede:field.id/}" title="{dede:field.typename/}txt电子书zip压缩包" target="_blank" onclick="_czc.push(['_trackEvent', '小说下载', 'zip', '{dede:field.typename/}','','']);">点击下载({dede:field.downloadurl function='round(filesize("..".str_replace(".zip",".txt",@me))/2160000,1)'/} MB)</a>
		  </div>
        </div>
        <div class="vote">说明：zip压缩包解压后可得到txt文件，txt文件比较大，请不要用记事本直接打开，除非你的电脑配置够好，否者会卡机或者假死。推荐用专业的文档编辑工具打开（如：Notepad++等），会省很多事。<!--AD-->{dede:myad name='booklist31'/}
        </div>
      </div>
    </div>
    <div class="Right">
      <div class="r_box tab">
        <div class="head"> <a class="l active" showBOX="BOX1">同类推荐</a> <a class="r" showBOX="BOX2">作者其他作品</a> </div>
        <div class="box BOX1" style="display:block;">
          <ul>
		  {dede:field.typeid runphp='yes'"}
			global $dsql ;
			$rows = $dsql->GetOne("SELECT reid,zuozhe FROM dede_arctype where id=@me");
			$reid = $rows['reid'];
			$zuozhe = $rows['zuozhe'];
			$content="";
			$n=1;
			$query = "select a.typename,a.typeimg,a.description,a.typedir,a.booksize,a.bookclick,a.startdate,a.zuozhe as zuozhe,b.typedir as zuozhedir from `#@__arctype` a left join `#@__arctype` b on(b.typename=a.zuozhe and b.reid=45) where a.reid='$reid' and a.id<>@me order by bookclick desc limit 0,10";
			$dsql->SetQuery($query);
			$dsql->Execute();
			while($row=$dsql->GetArray()){
				if($n==1)
				{
					$content.="<li><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'>".$row['typename']."</a><span><a href='/txt".$row['typedir'].".html' title='".$row['typename']."txt下载' target='_blank'>TXT下载</a></span></li><li class='first_con'><div class='pic'><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'><img class='lazy' src='".$row['typeimg']."' alt='".$row['typename']."' /></a></div>
				<div class='info'><p><a href='".$row['typedir']."/' target='_blank'>简介：".cn_substr(html2text($row['description']),50)."……</a></p>
				</div>
			</li>";
				}
				else
				{
					$content.="<li><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'>".$row['typename']."</a><span><a href='/txt".$row['typedir'].".html' title='".$row['typename']."txt下载' target='_blank'>TXT下载</a></span></li>";
				}
				$n++;
			}
		$content.='</ul>
        </div>
        <div class="box BOX2" style="display:none;">
          <ul>';
			$n=1;
			$query = "select a.typename,a.typeimg,a.description,a.typedir,a.booksize,a.bookclick,a.startdate,b.typename as retypename,b.typedir as retypedir from `#@__arctype` a left join `#@__arctype` b on(a.reid=b.id) where a.zuozhe='$zuozhe' and a.id<>@me order by startdate desc";
			$dsql->SetQuery($query);
			$dsql->Execute();
			while($row=$dsql->GetArray()){
				if($n==1)
				{
					$content.="<li><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'>".$row['typename']."</a><span><a href='/txt".$row['typedir'].".html' title='".$row['typename']."txt下载' target='_blank'>TXT下载</a></span></li><li class='first_con'><div class='pic'><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'><img class='lazy' src='".$row['typeimg']."' alt='".$row['typename']."' /></a></div>
				<div class='info'><p><a href='".$row['typedir']."/' target='_blank'>简介：".cn_substr(html2text($row['description']),50)."……</a></p>
				</div>
			</li>";
				}
				else
				{
					$content.="<li><a href='".$row['typedir']."/' title='".$row['typename']."' target='_blank'>".$row['typename']."</a><span><a href='/txt".$row['typedir'].".html' title='".$row['typename']."txt下载' target='_blank'>TXT下载</a></span></li>";
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
<div class="Layout no_h">
<div align="left">
<br/><h3>阅读提示：</h3><br/>
1、小说《<a href='{dede:field name='typedir'/}/' title="{dede:field name='typename'/}">{dede:field name='typename'/}</a>》所描述的内容只是作者【{dede:field.zuozhe /}】的个人写作观点，不保证其中情节的真实性，请勿模仿！<br/>
2、《<a href='{dede:field name='typedir'/}/' title="{dede:field name='typename'/}">{dede:field name='typename'/}</a>》版权归原作者【{dede:field.zuozhe /}】所有，本书仅代表作者本人的文学作品观点，仅供娱乐请莫当真。
</div>
</div>

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
			  alert("360、火狐等浏览器不支持自动添加收藏夹标签。关闭本对话框后，请您使用快捷键 Ctrl+D 进行添加。");  
		  }
	}
}
  </script>
  <script type="text/javascript" src="/js/xmlJS.js"></script>
  {dede:myad name='tongji'/}
</div>
{dede:myad name='booklist11'/}
{dede:myad name='booklist21'/}
</body>
</html>
