<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$user = auth()->user();
?>

<div class="nav-item">
    <a href="javascript:;" class="user-icon">
        <img src="<?= $user->avatar ?>" alt="<?=$user->name?>">
        <i class="drop-icon-arrow"></i>
    </a>
    <ul class="drop-bar">
        <li>
            <a href="<?=$this->url('/auth/admin/account')?>" class="no-jax">账户中心</a>
        </li>
        <li>
            <a href="<?=$this->url('/auth/admin/bulletin')?>" class="no-jax">消息</a>
        </li>
        <li>
            <hr class="drop-divider">
        </li>
        <li>
            <a href="<?=$this->url('/auth/admin/user')?>" class="no-jax">用户管理</a>
        </li>
        <li>
            <a href="<?=$this->url('/cms/admin')?>" class="no-jax">CMS管理</a>
        </li>
        <li>
            <a href="<?=$this->url('/seo/admin')?>" class="no-jax">系统设置</a>
        </li>
        <li>
            <a href="https://zodream.cn/doc" target="_blank">帮助</a>
        </li>
        <li>
            <hr class="drop-divider">
        </li>
        <li>
            <a href="<?=$this->url('/auth/logout')?>" class="no-jax">退出</a>
        </li>
    </ul>
</div>