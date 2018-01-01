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
          <li>{dede:global.cgf_top_left/}</li>
          <li><em class="ver">|</em><a href="{dede:global.cfg_wapurl/}" class="name" style="color:#F00; text-decoration:underline" title="在手机上阅读" target="_blank">手机版</a></li><li><em class="ver">|</em><a href="/over.html" class="name" style="color:#F00;" title="完本小说" target="_blank">完本小说</a></li><li><em class="ver">|</em><a href="/txt.html" class="name" style="color:#F00;" title="小说下载" target="_blank">小说下载</a></li>
        </ul>
      </div>
      <div class="right_con">
        <ul>
          <li><a href="/" title="返回首页">返回首页</a></li>
		{dede:php}
			global $dsql;
			$s="";
			$query = "SELECT * FROM dede_arctype WHERE reid=0 and id<>45 order by sortrank";
			$dsql->SetQuery($query);
			$dsql->Execute();
			while($row=$dsql->GetArray()){
				$s.='<li><em class="ver">|</em><a href="'.$row['typedir'].'.html" title="'.str_replace("·","",$row['typename']).'小说">'.str_replace("·","",$row['typename']).'</a></li>';
			}
			echo $s;
		{/dede:php}
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="topblank"></div>
<script type="text/javascript">if(getcookie("topbar_control")=="0"){$(".topblank").hide();}</script> 