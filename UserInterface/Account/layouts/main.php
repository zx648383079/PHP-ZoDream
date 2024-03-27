<?php
defined('APP_DIR') or exit();

use Domain\MenuLoader;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Template\View;
use Module\SEO\Domain\Option;
/** @var $this View */

$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@zodream.min.css',
    '@dialog.min.css',
    '@home.min.css',
    '@account.min.css'
])->registerJsFile([
    '@jquery.min.js',
    '@js.cookie.min.js',
    '@jquery.lazyload.min.js',
    '@jquery.dialog.min.js',
    '@main.min.js',
]);

$icp_beian = Option::value('site_icp_beian');
$pns_beian = Option::value('site_pns_beian');
$pns_beian_no = '';
if (!empty($pns_beian) && preg_match('/\d+/', $pns_beian, $match)) {
    $pns_beian_no = $match[0];
}
$user = UserRepository::getCurrentProfile('post_count,following_count,follower_count');
$menuItems = MenuLoader::loadMember();
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
        <link rel="icon" href="<?=$this->asset('images/favicon.png')?>">
       <?=$this->header();?>
   </head>
   <body>
        <header>
            <div class="container">
                <?=$this->node('nav-bar')?>
            </div>
        </header>
        <div class="container member-container">
            <div class="row">
                <div class="col-md-3">
                    <?php if($user):?>
                    <div class="person-box">
                        <div class="avatar">
                            <img src="<?= $user['avatar'] ?>" alt="<?= $this->text($user['name']) ?>">
                        </div>
                        <div class="name">
                            <?= $this->text($user['name']) ?>
                            <?php if($user['is_verified']):?>
                            <i class="fa fa-shield-alt" title="<?=__('This user is verified')?>"></i>
                            <?php endif;?>
                        </div>
                        <div class="meta">uid: <?= $user['id'] ?></div>
                        <div class="desc"></div>
                        <a class="btn btn-primary"><?=__('Edit Profile')?></a>
                        <div class="count-bar">
                            <div class="count-item">
                                <div class="count"><?= intval($user['following_count']) ?></div>
                                <span><?=__('Following')?></span>
                            </div>
                            <div class="count-item">
                                <div class="count"><?= intval($user['follower_count']) ?></div>
                                <span><?=__('Followers')?></span>
                            </div>
                            <div class="count-item">
                                <div class="count"><?= intval($user['post_count']) ?></div>
                                <span><?=__('Posts')?></span>
                            </div>
                        </div>

                        <div class="link-panel">
                            <a>
                                <i class="fa fa-map-marker"></i>
                                <?= $user['country'] ?>
                            </a>
                            <?php if($user['mobile']):?>
                            <a>
                                <i class="fa fa-mobile-alt"></i>
                                <?= $user['mobile'] ?>
                            </a>
                            <?php endif;?>
                            <a>
                                <i class="fa fa-mail-bulk"></i>
                                <?= $user['email'] ?>
                            </a>
                        </div>
                    </div>
                    <?php endif;?>  
                </div>
                <div class="col-12 col-md-9">
                    <div class="tab-nav-bar">
                        <div class="tab-bar">
                            <?php foreach($menuItems as $i => $item):?>
                            <?php if($i < 4):?>
                            <a class="item" href="<?= $item['url'] ?>"><?= $item['label'] ?></a>
                            <?php endif;?>
                            <?php endforeach;?>
                        </div>
                        <div class="nav-more-bar">
                            <div class="more-icon">
                                <i class="fa fa-ellipsis-v"></i>
                            </div>
                            <div class="more-body">
                                <?php foreach($menuItems as $item):?>
                                <a class="item" href="<?= $item['url'] ?>">
                                        <i class="<?= $item['icon'] ?>"></i>
                                        <span><?= $item['label'] ?></span>
                                    </a>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-nav-body">
                    <?=$this->contents()?>
                    </div>
                </div>
            </div>
        </div>


        
        <footer>
            <div class="container">
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
        <?=$this->footer()?>
   </body>
</html>