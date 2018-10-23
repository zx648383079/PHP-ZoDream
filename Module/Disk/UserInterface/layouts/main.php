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
        '@disk.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@vue.js',
        '@main.min.js',
        '@disk.min.js'
    ]);
?>

<?= Layout::main($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '全部文件',
        false,
        'fa fa-files-o',
        [
            [
                '图片',
                './disk#type/1',
                'fa fa-image'
            ],
            [
                '文档',
                './disk#type/2',
                'fa fa-file-word-o'
            ],
            [
                '视频',
                './disk#type/3',
                'fa fa-file-video-o'
            ],
            [
                '种子',
                './disk#type/4',
                'fa fa-gift'
            ],
            [
                '音乐',
                './disk#type/5',
                'fa fa-file-sound-o'
            ],
            [
                '其他',
                './disk#type/6',
                'fa fa-file-zip-o'
            ],
        ]
    ],
    [
        '我的分享',
        './share/my',
        'fa fa-share-alt'
    ],
    [
        '回收站',
        './trash',
        'fa fa-trash'
    ]
], 'ZoDream Disk') ?>
