<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\SEO\Domain\Option;
/** @var $this View */

$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@zodream.min.css',
    '@book.min.css',
])->registerJsFile([
    '@jquery.min.js',
    '@js.cookie.min.js',
    '@jquery.lazyload.min.js',
    '@main.min.js',
    '@book.min.js'
]);

$icp_beian = Option::value('site_icp_beian');
$pns_beian = Option::value('site_pns_beian');
$pns_beian_no = '';
if (!empty($pns_beian) && preg_match('/\d+/', $pns_beian, $match)) {
    $pns_beian_no = $match[0];
}

?>
<!DOCTYPE html>
<html lang="<?=trans()->getLanguage()?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$this->text($this->title)?>-<?=__('site title')?></title>
    <meta name="Keywords" content="<?=$this->text($this->get('keywords'))?>" />
    <meta name="Description" content="<?=$this->text($this->get('description'))?>" />
    <meta name="author" content="zodream" />
    <?=$this->header();?>
</head>
<body>
    <header class="header">
        <div class="top-nav-bar">
            <div class="container">
                <a href="">常见问题</a>
                <a href="<?=$this->url('./history')?>">我的书架</a>
                <a href="<?=$this->url('./history')?>">浏览记录</a>
            </div>
        </div>
        <div class="large-nav-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="title-bar">
                            <h2>ZODREAM</h2>
                            <span>读书可以明智</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form class="search-bar" action="<?=$this->url('./search')?>">
                            <input type="text" class="form-control" name="keywords">
                            <button class="btn btn-default">搜索</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-nav-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="nav-item with-drop">
                            <a href="<?=$this->url('./search')?>">全部分类</a>
                            <div class="drop-bar">
                                <?php foreach($cat_list as $item):?>
                                <a href="<?=$this->url('./category', ['id' => $item['id']])?>" class="cat-mini-item">
                                    <div class="item-icon">
                                        <img src="<?=$item['thumb']?>" alt="<?=$item['name']?>">
                                    </div>
                                    <div class="item-body">
                                        <div class="item-name"><?=$item['name']?></div>
                                        <div class="item-meta"><?=intval($item['book_count'])?></div>
                                    </div>
                                </a>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <a href="<?=$this->url('./')?>" class="nav-item">首页</a>
                        <a href="<?=$this->url('./search/top')?>" class="nav-item">排行榜</a>
                        <a href="<?=$this->url('./search', ['status' => 2])?>" class="nav-item">完本</a>
                        <a href="<?=$this->url('./list')?>" class="nav-item">书单</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?=$this->contents()?>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
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
            </div>
        </div>
    </footer>

    <?=$this->footer()?>
</body>
</html>