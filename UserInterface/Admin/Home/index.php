<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head'
    )), array(
        'bui' => array(
            'bui-min.css',
            'main-min.css'
        ),
        'zodream/main.css'
    )
);
?>

    <div class="header">

        <div class="dl-title">
            <a href="<?php $this->url('/');?>" title="ZoDream">
                <span class="lp-title-port">ZoDream</span><span class="dl-title-text">后台管理系统</span>
            </a>
        </div>

        <div class="dl-log">欢迎您，<span class="dl-log-user"><?php $this->out('name');?></span>
            <a href="<?php $this->url('account.php/auth/logout');?>" title="退出系统" class="dl-log-quit">[退出]</a>
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
<?php
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        '!js require(["admin/main"]);'
    )
);
?>