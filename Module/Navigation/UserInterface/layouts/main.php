<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->registerCssFile([
    '@font-awesome.min.css',
    '@zodream.min.css',
    '@dialog.min.css',
    '@home.min.css',
    '@searcher.min.css'
])->registerJsFile([
    '@jquery.min.js',
    '@js.cookie.min.js',
    '@jquery.dialog.min.js',
    '@main.min.js',
    '@searcher.min.js'
])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>
<!DOCTYPE html>
<html lang="<?=trans()->getLanguage()?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width, initial-scale=1">
        <title><?=$this->text($this->title)?>-<?=__('site title')?></title>
        <meta name="Keywords" content="<?=$this->text($this->get('keywords'))?>" />
        <meta name="Description" content="<?=$this->text($this->get('description'))?>" />
        <meta name="author" content="zodream" />
        <?php if($this->layout_og):?>
        <!-- og 协议 -->
            <?php foreach($this->layout_og as $key => $item):?>
                <meta property="og:<?=$key?>" content="<?=$item?>"/>
            <?php endforeach;?>
        <?php endif;?>
        <link rel="icon" href="<?=$this->asset('images/favicon.png')?>">
        <?=$this->header();?>
    </head>
<body>
    
    <?=$this->contents()?>
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
    <?=$this->footer()?>
</body>
</html>