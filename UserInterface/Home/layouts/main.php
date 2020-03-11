<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@zodream.css',
    '@dialog.css',
    '@home.css'
])->registerJsFile([
    '@jquery.min.js',
    '@jquery.cookie.js',
    '@jquery.lazyload.min.js',
    '@jquery.dialog.min.js',
    '@main.min.js',
    '@home.min.js'
]);
?>
<!DOCTYPE html>
<html lang="<?=trans()->getLanguage()?>">
   <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width, initial-scale=1">
        <title><?=$this->title?>-<?=__('site title')?></title>
        <meta name="Keywords" content="<?=$this->get('keywords')?>" />
        <meta name="Description" content="<?=$this->get('description')?>" />
        <meta name="author" content="zodream" />
        <link rel="icon" href="/assets/images/favicon.png">
        <link rel="alternate" type="application/rss+xml" title="<?=__('site title')?>" href="<?=$this->url('/blog/rss', false)?>">
       <?=$this->header();?>
   </head>
   <body>
        <header>
            <div class="container">
                <?=$this->node('nav-bar')?>
            </div>
        </header>
        <?=$content?>
        <footer>
            <div class="container">
                <?=$this->node('friend-link')?>
                <div class="copyright">
                    <p>Copyright ©zodream.cn, All Rights Reserved.</p>
                    <a href="http://www.beian.miit.gov.cn" target="_blank">湘ICP备16003508号</a>
                    <p>
                        <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=43052402000190">
                            <img src="<?=$this->asset('images/beian.png')?>" alt="备案图标">
                        湘公网安备 43052402000190号
                        </a>
                    </p>
                </div>
            </div>
        </footer>
        <div class="dialog-search">
            <i class="dialog-close"></i>
            <div class="dialog-body">
                <form action="" method="get">
                    <i class="input-search"></i>
                    <input type="text" name="keywords" value="<?=$this->text(app('request')->get('keywords'))?>" placeholder="请输入关键字，按回车 / Enter 搜索" autocomplete="off">
                    <i class="input-clear"></i>
                </form>
                <ul class="search-suggestion">
                </ul>
            </div>        
        </div>
        <?php if(date('d') === 1 && auth()->guest()):?>
        <div class="dialog-cookie-tip">
            <div class="dialog-body">
                <?=__('cookie tip')?>
                <a href="<?=$this->url('about')?>"><?=__('READ MORE')?></a>
            </div>
            <div class="dialog-footer">
                <button class="btn">OK</button>
            </div>
        </div>
        <?php endif;?>
        <?=$this->footer()?>
   </body>
</html>