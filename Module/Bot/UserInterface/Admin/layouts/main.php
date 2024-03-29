<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.min.css',
        '@zodream-admin.min.css',
        '@editor.min.css',
        '@dialog.min.css',
        '@bot.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@jquery.editor.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@bot.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '消息管理',
        false,
        'fa fa-comments',
        [
            [
                '关注回复',
                ['./@admin/reply', 'event' => 'subscribe'],
                'fa fa-list'
            ],
            [
                '自动回复',
                ['./@admin/reply', 'event' => 'default'],
                'fa fa-list'
            ],
            [
                '关键字回复',
                './@admin/reply',
                'fa fa-list'
            ],
            [
                '群发消息',
                './@admin/reply/all',
                'fa fa-list'
            ],
            [
                '模板消息',
                './@admin/reply/template',
                'fa fa-list'
            ]
        ]
    ],
    [
        '素材管理',
        false,
        'fa fa-cubes',
        [
            [
                '图文消息',
                ['./@admin/media', 'type' => 'news'],
                'fa fa-list',
            ],
            [
                '图片',
                ['./@admin/media', 'type' => 'image'],
                'fa fa-image',
            ],
            [
                '语音',
                ['./@admin/media', 'type' => 'voice'],
                'fa fa-music',
            ],
            [
                '视频',
                ['./@admin/media', 'type' => 'video'],
                'fa fa-video',
            ],
            [
                '图文模板管理',
                ['./@admin/template'],
                'fa fa-list',
            ],
        ]
    ],
    [
        '菜单管理',
        './@admin/menu',
        'fa 
        fa-puzzle-piece',
    ],
    [
        '用户管理',
        false,
        'fa fa-users',
        [
            [
                '已关注',
                './@admin/user',
                'fa fa-list'
            ],
            [
                '黑名单',
                ['./@admin/user', 'blacklist' => 1],
                'fa fa-bomb'
            ]
        ]
    ],
    [
        '记录管理',
        false,
        'fa fa-paw',
        [
            [
                '全部消息',
                './@admin/log',
                'fa fa-list'
            ],
            [
                '已收藏的消息',
                ['./@admin/log', 'mark' => 1],
                'fa fa-heart'
            ]
        ]
    ],
    [
        '公众号管理',
        false,
        'fa fa-briefcase',
        [
            [
                '公众号列表',
                './@admin/manage',
                'fa fa-list'
            ]
        ],
        true
    ]
], $this->contents(), 'ZoDream WeChat') ?>
