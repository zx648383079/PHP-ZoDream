<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    )), 'zodream/main.css'
);
?>
<nav class="topMenu">
    <ul>
        <li title="系统设置" data="system">系统</li>
        <li title="信息管理" data="index">信息</li>
        <li title="栏目管理" data="column">栏目</li>
        <li title="模板管理" data="template">模板</li>
        <li title="用户和会员" data="user">用户</li>
        <li title="插件管理" data="tool">插件</li>
        <li title="商城系统管理" data="shop">商城</li>
        <li title="其他管理" data="other">其他</li>
        <li title="扩展菜单项" data="extend">扩展</li>
        <li title="常用操作" data="">常用</li>
    </ul>
</nav>
<nav class="secondMenu">
    <ul>
        <li data="easy">增加信息</li>
        <li>管理信息</li>
        <li>审核信息</li>
        <li>签发信息</li>
        <li>管理评论</li>
        <li>更新碎片</li>
        <li>更新专题</li>
        <li>数据更新</li>
        <li>数据统计</li>
        <li>排行统计</li>
        <li data="admin/main">后台首页</li>
        <li>网站首页</li>
        <li data="admin/map">后台地图</li>
        <li>版本更新</li>
    </ul>
</nav>
<div class="bigContainer">
    <div id="leftMenu" title="左侧菜单">
        <?php $this->extend('Admin/Menu/index');?>
    </div>
    <div class="frame">
        <iframe id="main" src="admin/main" title="主体">
        </iframe>
    </div>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>