<?php
/** @var $this \Zodream\Template\View */
$this->registerJsFile('@jquery.min.js');
?>
<div class="footer">
	<div class="section nav">
		<p>
            <a href="<?=$this->url('./wap')?>">首页</a>
            <a href="<?=$this->url('./wap/top')?>">排行</a>
            <a href="<?=$this->url('./wap/list', ['status' => 2])?>">全本</a>
            <a href="<?=$this->url('./wap/log')?>">阅读记录</a>
		</p>
	</div>
	<div class="section copyright">
		<p class="copy"><a href="<?=$this->url('./wap')?>"><?=$site_name?>移动版</a>
            <a href="<?=$this->url('./wap')?>"><?=$this->url('./wap')->getHost()?></a></p>
		<p><span class="time">版权声明：本站小说为转载作品，所有章节均由网友上传，转载至本站只是为了宣传本书让更多读者欣赏。</span></p>
	</div>
</div>
<div class="slide-ad"><!--广告--></div>