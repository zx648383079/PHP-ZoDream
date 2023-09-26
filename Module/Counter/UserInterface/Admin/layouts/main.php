<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;

/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@datetimer.css',
        '@counter.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@jquery.lazyload.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@counter.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '网站概况',
        './@admin',
        'fa fa-home',
    ],
    [
        '流量分析',
        false,
        'fa fa-chart-line',
        [
            [
                '实时访客',
                './@admin/trend',
                'fa fa-'
            ],
            [
                '趋势分析',
                './@admin/trend/time',
                'fa fa-'
            ]
        ],
        true
    ],
    [
        '来源分析',
        false,
        'fa fa-chart-pie',
        [
            [
                '全部来源',
                './@admin/source',
                'fa fa-'
            ],
            [
                '搜索引擎',
                './@admin/source/engine',
                'fa fa-'
            ],
            [
                '搜索词',
                './@admin/source/search_word',
                'fa fa-'
            ],
            [
                '外部链接',
                './@admin/source/link',
                'fa fa-'
            ]
        ],
        true
    ],
    [
        '访问分析',
        false,
        'fa fa-dna',
        [
            [
                '受访页面',
                './@admin/visit',
                'fa fa-'
            ],
            [
                '入口页面',
                './@admin/visit/enter',
                'fa fa-'
            ],
            [
                '受访域名',
                './@admin/visit/domain',
                'fa fa-'
            ],
            [
                '页面点击图',
                './@admin/custom/page_click',
                'fa fa-'
            ],
        ],
        true
    ],
    [
        '访客分析',
        false,
        'fa fa-user',
        [
            [
                '地域分布',
                './@admin/visit/district',
                'fa fa-'
            ],
            [
                '系统环境',
                './@admin/visit/client',
                'fa fa-'
            ],
        ],
        true
    ],
], $content, 'ZoDream Counter Admin') ?>
