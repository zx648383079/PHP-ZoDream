<?php
defined('APP_DIR') || die();
/** @var $this \Zodream\Domain\View\View */
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

<?php
$this->extend('layout/footer');
?>