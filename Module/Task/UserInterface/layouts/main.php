<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@datetimer.css',
        '@dialog.css',
        '@task.css'
    ])->registerJsFile([
        '@echarts.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@task.min.js'
    ]);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '任务管理',
        false,
        'fa fa-business-time',
        [
            [
                '今日任务',
                './task/today',
                'fa fa-list'
            ],
            [
                '任务列表',
                './task',
                'fa fa-list'
            ],
            [
                '新建任务',
                './task/create',
                'fa fa-plus'
            ]
        ],
        true
    ],
    [
        '工作统计',
        false,
        'fa fa-chart-line',
        [
            [
                '周视图',
                './record?type=week',
                'fa fa-chart-area'
            ],
            [
                '月视图',
                './record?type=month',
                'fa fa-chart-bar'
            ]
        ],
    ],
    [
        '工作记录',
        false,
        'fa fa-history',
        [
            [
                '日视图',
                './review?type=day',
                'fa fa-clock'
            ],
            [
                '周视图',
                './review?type=week',
                'fa fa-calendar'
            ],
            [
                '月视图',
                './review?type=month',
                'fa fa-calendar-alt'
            ]
        ],
    ]
], $content, 'ZoDream Task') ?>
