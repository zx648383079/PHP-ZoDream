<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$js = <<<JS
var footer = $('footer'),
    diff = $(window).height() - footer.offset().top - footer.height();
if (diff > 0) {
    footer.css('margin-top', diff + 'px');
}
if (!$.cookie('c_t')) {
    $('.dialog-cookie-tip').show();
}
$('.dialog-cookie-tip .btn').click(function() {
    $.cookie('c_t', 1);
    $(this).closest('.dialog-cookie-tip').hide();
});
JS;

$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@zodream.css',
    '@home.css'
])->registerJsFile([
    '@jquery.min.js',
    '@jquery.cookie.js',
    '@jquery.lazyload.min.js'
])->registerJs($js, View::JQUERY_READY);
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
                    <a href="http://www.beian.miit.gov.cn" target="_blank">湘ICP备16003508号</a>
                </div>
            </div>
        </footer>
        <div class="dialog-cookie-tip">
            <div class="dialog-body">
                <?=__('cookie tip')?>
                <a href="<?=$this->url('about')?>"><?=__('READ MORE')?></a>
            </div>
            <div class="dialog-footer">
                <button class="btn">OK</button>
            </div>
        </div>
        <?=$this->footer()?>
   </body>
</html>