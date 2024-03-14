<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\SEO\Domain\Option;
/** @var $this View */

$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@zodream.min.css',
    '@dialog.min.css',
    '@home.min.css'
])->registerJsFile([
    '@jquery.min.js',
    '@js.cookie.min.js',
    '@jquery.lazyload.min.js',
    '@jquery.dialog.min.js',
    '@main.min.js',
    '@home.min.js'
]);

$icp_beian = Option::value('site_icp_beian');
$pns_beian = Option::value('site_pns_beian');
$pns_beian_no = '';
if (!empty($pns_beian) && preg_match('/\d+/', $pns_beian, $match)) {
    $pns_beian_no = $match[0];
}
?>
<!DOCTYPE html>
<html lang="<?=app()->getLocale()?>">
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
        <?php if($this->has('rss_show')):?>
            <link rel="alternate" type="application/rss+xml" title="<?=__('site title')?>" href="<?=$this->url('/blog/rss', false)?>">
        <?php endif;?>
       <?=$this->header();?>
   </head>
   <body>
        <header>
            <div class="container">
                <?=$this->node('nav-bar')?>
            </div>
        </header>
        <?=$this->contents()?>
        <footer>
            <div class="container">
                <?=$this->node('friend-link')?>
                <div class="copyright">
                    <p>Copyright ©<?= request()->host() ?>, All Rights Reserved.</p>
                    <?php if($icp_beian):?>
                    <a href="https://beian.miit.gov.cn/" target="_blank"><?= $icp_beian ?></a>
                    <?php endif;?>
                    <?php if($pns_beian):?>
                    <p>
                        <a target="_blank" href="https://www.beian.gov.cn/portal/registerSystemInfo?recordcode=<?= $pns_beian_no ?>">
                            <img src="<?=$this->asset('images/beian.png')?>" alt="备案图标">
                            <?= $pns_beian ?>
                        </a>
                    </p>
                    <?php endif;?>
                </div>
            </div>
        </footer>
        <div class="dialog-search">
            <i class="dialog-close"></i>
            <div class="dialog-body">
                <form action="<?= $layout_search_url ?? $this->url('./') ?>" method="get">
                    <i class="input-search"></i>
                    <input type="text" name="keywords" value="<?=$this->text(request()->get('keywords'))?>" placeholder="请输入关键字，按回车 / Enter 搜索" autocomplete="off">
                    <i class="input-clear"></i>
                </form>
                <ul class="search-suggestion">
                </ul>
            </div>        
        </div>
        <?php if(auth()->guest()):?>
        <?=$this->node('cookie-bar')?>
        <?php endif;?>
        <?=$this->footer()?>
   </body>
</html>