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
        '@disk.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@vue.js',
        '@main.min.js',
        '@disk.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./', false)), View::HTML_HEAD);
?>

<?= Layout::main($this, [
    [
        '首页',
        './',
        'fa fa-home',
    ],
    [
        '全部文件',
        './disk',
        'fa fa-folder-open',
        [
            [
                '图片',
                './disk#type/image',
                'fa fa-image'
            ],
            [
                '文档',
                './disk#type/doc',
                'fa fa-file-word'
            ],
            [
                '视频',
                './disk#type/video',
                'fa fa-file-video'
            ],
            [
                '种子',
                './disk#type/bt',
                'fa fa-gift'
            ],
            [
                '音乐',
                './disk#type/music',
                'fa fa-music'
            ],
            [
                'APP',
                './disk#type/app',
                'fa fa-boxes'
            ],
            [
                '其他',
                './disk#type/archive',
                'fa fa-file-archive'
            ],
        ],
        true
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
], $this->contents()) ?>
