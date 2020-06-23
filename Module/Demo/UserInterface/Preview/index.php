<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '预览'.$post->title;
$this->registerCssFile('@demo.css')
    ->registerJsFile('@demo.min.js')
    ->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>

<div class="frame-resize">
    <a href="">全屏</a>
    <a href="">768x1024</a>
    <a href="">1024x768</a>
    <a href="">375x812</a>
    <a href="">812x275</a>
</div>

<div class="frame-box">
    <iframe id="main-frame" src="<?=$this->url('./preview/view/0/id/'.$post->id.'/file/0/')?>" frameborder="0"></iframe>
</div>