<?php
defined('APP_DIR') || die();
use Zodream\Domain\Html\Bootstrap\TabWidget;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');
$this->extend('layout/crumb', [
    'title' => '系统配置-系统功能',
    'links' => [
        '首页' => 'main',
        '系统功能'
    ]
]);
?>

<!-- Main content -->
<section class="content">
    <?=TabWidget::show([
        'items' => [
            [
                'title' => '<i class="fa fa-gear"></i>基本设置',
                'content' => '',
                'active' => true
            ],
            [
                'title' => '<i class="fa fa-cubes"></i>后台设置',
                'content' => '',
            ],
            [
                'title' => '<i class="fa fa-location-arrow"></i>优化设置',
                'content' => '',
            ],
            [
                'title' => '<i class="fa fa-clock-o"></i>缓存时间',
                'content' => '',
            ],
            [
                'title' => '<i class="fa fa-get-pocket"></i>数据安全',
                'content' => '',
            ],
            [
                'title' => '<i class="fa fa-rmb"></i>交易配置',
                'content' => '',
            ]
        ]
    ])?>
</section>

<?php
$this->extend('layout/footer');
?>
