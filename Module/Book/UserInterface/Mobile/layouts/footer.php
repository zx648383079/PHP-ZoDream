<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
      ->registerJsFile('@book.min.js');
?>
    <div class="footer">
	<div class="section nav">
		<p>
            <a href="<?=$this->url('./mobile')?>">首页</a>
            <a href="<?=$this->url('./mobile/search/top')?>">排行</a>
            <a href="<?=$this->url('./mobile/search/list', ['status' => 2])?>">全本</a>
            <a href="<?=$this->url('./mobile/history')?>">阅读记录</a>
		</p>
	</div>
	<div class="section copyright">
		<p class="copy"><a href="<?=$this->url('./mobile')?>"><?=$site_name?>移动版</a>
            <a href="<?=$this->url('./mobile')?>"><?=url('/')?></a></p>
		<p><span class="time">版权声明：本站小说为转载作品，所有章节均由网友上传，转载至本站只是为了宣传本书让更多读者欣赏。</span></p>
	</div>
</div>
<div class="slide-ad"><!--广告--></div>
<?php if(!app()->isDebug()):?>
        <div class="demo-tip"></div>
    <?php endif;?>
   <?=$this->footer()?>
   </body>
</html>