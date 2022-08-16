<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '预览'.$post->title;
$this->registerCssFile('@demo.css')
    ->registerJsFile('@demo.min.js')
    ->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>

<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>">Demo首页</a>
        </li>
        <li class="active">
            预览 <?=$this->text($post->title)?> 
        </li>
    </ul>
</div>

<div class="frame-resize">
    <a href="<?=$this->url('./preview/view/0/id/'.$post->id.'/file/0/', false)?>" target="_blank">
        <i class="fa fa-times"></i>
        移除其他内容
    </a>
    <a href="">全屏</a>
    <a href="">768x1024</a>
    <a href="">1024x768</a>
    <a href="">375x812</a>
    <a href="">812x375</a>
</div>

<div class="frame-box">
    <iframe id="main-frame" src="<?=$this->url('./preview/view/0/id/'.$post->id.'/file/0/', false)?>" frameborder="0"></iframe>
</div>