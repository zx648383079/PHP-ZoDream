<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Url\Url;
/** @var $this \Zodream\Domain\View\View */
$this->title = '后台管理';
$this->registerCssFile('bui/bui-min.css');
$this->registerCssFile('bui/main-min.css');
$this->registerCssFile('zodream/main.css');
$this->registerJs('require(["admin/main"]);');
$this->extend('layout/head');
?>

    <div class="header">

        <div class="dl-title">
            <a href="<?=Url::to('/')?>" title="ZoDream">
                <span class="lp-title-port">ZoDream</span><span class="dl-title-text">后台管理系统</span>
            </a>
        </div>

        <div class="dl-log">欢迎您，<span class="dl-log-user"><?=$name?></span>
            <a href="<?=Url::to('account.php/auth/logout');?>" title="退出系统" class="dl-log-quit">[退出]</a>
        </div>
    </div>
    <div class="content">
        <div class="dl-main-nav">
            <div class="dl-inform"><div class="dl-inform-title">贴心小秘书<s class="dl-inform-icon dl-up"></s></div></div>
            <ul id="J_Nav"  class="nav-list ks-clear">
                <li class="nav-item dl-selected">
                    <div class="nav-item-inner nav-home">首页</div>
                </li>
                <li class="nav-item">
                    <div class="nav-item-inner nav-order">功能</div>
                </li>
                <li class="nav-item">
                    <div class="nav-item-inner nav-inventory">插件</div></li>
                <li class="nav-item">
                    <div class="nav-item-inner nav-supplier">用户</div></li>
                <li class="nav-item">
                    <div class="nav-item-inner nav-marketing">数据</div></li>
            </ul>
        </div>
        <ul id="J_NavContent" class="dl-tab-conten">

        </ul>
    </div>

<?=$this->extend('layout/foot')?>