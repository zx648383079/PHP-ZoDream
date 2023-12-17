<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.min.css',
        '@zodream-admin.min.css',
        '@dialog.min.css',
        '@datetimer.min.css',
        '@finance.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@echarts.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@finance.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '资金',
        false,
        'fa fa-money-bill',
        [
            [
                '总资本',
                './money',
                'fa fa-list-alt'
            ],
            [
                '资金账户',
                './money/account',
                'fa fa-credit-card'
            ],
            [
                '理财项目',
                './money/project',
                'fa fa-trophy'
            ],
            [
                '理财产品',
                './money/product',
                'fa fa-cubes'
            ]
        ],
    ],
    [
        '收支管理',
        false,
        'fa fa-puzzle-piece',
        [
            [
                '月收支',
                './income',
                'fa fa-exchange-alt'
            ],
            [
                '月流水',
                './income/log',
                'fa fa-recycle'
            ],
            [
                '消费渠道',
                './income/channel',
                'fa fa-anchor'
            ],
        ],
        true
    ],
    [
        '生活预算',
        './budget',
        'fa fa-tasks'
    ]
], $this->contents(), 'ZoDream Finance') ?>
