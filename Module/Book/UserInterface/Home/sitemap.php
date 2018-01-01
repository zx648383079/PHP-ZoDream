<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={dede:global.cfg_soft_lang/}" />
<title>{dede:global.cfg_webname/}_网站地图_全部小说</title>
<link href="/css/sitemap.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="mapspage">
<div class="w960 clear center mt1">
	<div class="sp-title">
    <h2>{dede:global.cfg_webname/}-网站地图-全部小说</h2>
   <span class="more"><a href='{dede:global.cfg_basehost/}'>返回首页</a></span>
    </div>
	{dede:php}
	global $dsql,$cfg_wapurl,$cfg_basehost;
	$query = "Select id,typedir,typename From `#@__arctype` where reid=0 And channeltype=1 And ishidden=0 And id not in(375,376,6981) And ispart<>2 order by sortrank";
	$dsql->SetQuery($query);
	$dsql->Execute();
	$a=0;
	while($row=$dsql->GetArray()){
		$reid[$a]=$row['id'];
		$retypename[$a]=$row['typename'];
		$retypedir[$a]=$row['typedir'];
		$a++;
	}
	for($m=0;$m<$a;$m++)
	{
		echo '<div class="linkbox">
		<h3><a href="'.$retypedir[$m].'.html">'.$retypename[$m].'</a></h3>
		<ul class="f6">';
		$query = "Select id,typedir,typename From `#@__arctype` where reid=".$reid[$m]." order by id";
		$dsql->SetQuery($query);
		$dsql->Execute();
		while($row=$dsql->GetArray())
		{
			echo "<li><a href='".$row['typedir']."/'>".$row['typename']."</a></li><li><a href='/txt".$row['typedir'].".html'>".$row['typename']."txt下载</a></li>";
		}
		echo '</ul></div>';
	}
	echo '<div class="linkbox">
	<h3>作品集</h3>
	<ul class="f6">';
	$query = "Select id,typedir,typename From `#@__arctype` where reid=45";
	$dsql->SetQuery($query);
	$dsql->Execute();
	while($row=$dsql->GetArray())
	{
		echo "<li><a href='".$row['typedir'].".html'>".$row['typename']."作品集</a></li>";
	}
	echo '</ul></div>
	<div class="linkbox">
		<h3>最新章节</h3>
		<ul class="f6">';
	$query = "Select a.id,a.title,b.typedir,b.typename FROM `#@__archives` a left join `#@__arctype` b on (b.id=a.typeid) order by id desc limit 5000";
	$dsql->SetQuery($query);
	$dsql->Execute();
	while($row=$dsql->GetArray())
	{
		echo "<li><a href='".$cfg_basehost.$row['typedir']."/'>[".$row['typename']."]</a><a href='".$cfg_basehost.$row['typedir']."/".$row['id'].".html'>".$row['title']."</a></li>	";
	}
	echo '</ul></div>';
	{/dede:php}
</div>
<div class="footer w960 center mt1 clear">
    <div class="footer_body">
	<p class="powered">    
		Powered by <a href="{dede:global.cfg_basehost/}/" title="{dede:global.cfg_webname/}" target="_blank"><strong>{dede:global.cfg_webname/}</strong></a> &#169; 2013-2014</p>
   </div>
</div>
</body>
</html>
