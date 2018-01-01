<?php
/** @var $this \Zodream\Template\View */
$this->registerJsFile('@jquery.min.js');
?>
<div class="footer">
	<div class="section nav">
		<p>
			<a href="/">首页</a>
			<a href="/wap.php?action=top">排行</a>
			<a href="/wap.php?action=shuku">书库</a>
			<a href="/wap.php?action=shuku&over=1">全本</a>
			<a href="/wap.php?action=jilu">阅读记录</a>
		</p>
	</div>
	<div class="section copyright">
		<p class="copy"><a href="<?php echo $cfg_wapurl."/".$wxuid2; ?>"><?php echo $cfg_webname; ?>移动版(1.0)</a>   <a href="<?php echo $cfg_wapurl."/".$wxuid2; ?>"><?php echo str_replace("http://","",$cfg_wapurl); ?></a></p>
		<p><span class="time">版权声明：<?php echo str_replace(array("<p>","</p>"),"",$novel_powerby); ?></span></p>
	</div>
	<div style="display:none"><?php echo $cfg_waptj; ?></div>
</div>
<div class="slide-ad"><!--广告--></div>