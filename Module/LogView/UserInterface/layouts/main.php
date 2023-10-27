<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$file_menu = [];

if (isset($file_list)) {
    foreach($file_list as $item) {
        $file_menu[] = [
            $item['name'],
            ['./log', 'id' => $item['id']],
            'fa fa-file'
        ];
    }
}
$file_menu[] = [
    '上传文件',
    './log/create',
    'fa fa-plus',
];
$file_menu[] = [
    '清除全部',
    './log/clear',
    'fa fa-trash',
];

$this->registerCssFile([
    '@font-awesome.min.css',
    '@prism.css',
    '@zodream.css',
    '@zodream-admin.css',
    '@log.css'
])->registerJsFile([
    '@jquery.min.js',
    '@main.min.js',
    '@admin.min.js',
    '@log.min.js'
]);
?>

<?= Layout::main($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '文件列表',
        false,
        'fa fa-th-list',
        $file_menu
    ],
    [
        '智能分析',
        false,
        'fa fa-user-md',
        [
            [
                '次数曲线图',
                './analysis',
                'fa fa-line-chart'
            ],
            [
                '统计排行',
                './analysis/top',
                'fa fa-list-ol'
            ],
            [
                '可疑推断',
                './analysis/infer',
                'fa fa-list-ol'
            ]
        ]
    ]
], $this->contents(), 'ZoDream Log Viewer') ?>
