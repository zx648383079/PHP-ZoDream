<?php
defined('APP_DIR') || die();
/** @var $this \Zodream\Template\View */
$this->title = '仪表板';
$this->extend('layout/header');
$this->extend('layout/crumb', [
    'title' => '控制面板-仪表板',
    'links' => [
        '首页' => 'main',
        '仪表板'
    ]
]);
?>
<div class="page-bar">
    关于我们
</div>
<div class="container">
    hhhh 
</div>
<?php
$this->extend('layout/footer');
?>