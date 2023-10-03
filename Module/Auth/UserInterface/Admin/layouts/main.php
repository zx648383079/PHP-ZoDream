<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;

/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@select2.min.css',
        '@dialog.css',
        '@auth_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@select2.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@auth_admin.min.js'
    ])->registerJs(sprintf('var BASE_URI="%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);

$user = auth()->user();
$isAdministrator = $user && $user->isAdministrator()
?>


<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '用户管理',
        false,
        'fa fa-users',
        [
            [
                '用户列表',
                './@admin/user',
                'fa fa-list'
            ],
            [
                '新增用户',
                './@admin/user/create',
                'fa fa-plus'
            ]
        ],
        true,
        false,
        $isAdministrator
    ],
    [
        '权限管理',
        false,
        'fa fa-magnet',
        [
            [
                '角色列表',
                './@admin/role',
                'fa fa-list'
            ],
            [
                '新增角色',
                './@admin/role/create',
                'fa fa-plus'
            ],
            [
                '权限列表',
                './@admin/permission',
                'fa fa-list'
            ],
            [
                '新增权限',
                './@admin/permission/create',
                'fa fa-plus'
            ]
        ],
        false,
        false,
        $isAdministrator
    ],
    [
        '第三方管理',
        false,
        'fa fa-bus',
        [
            [
                '接口配置',
                './@admin/oauth/option',
                'fa fa-cog'
            ],
        ],
        true,
        false,
        $isAdministrator
    ],
    [
        '消息管理',
        false,
        'fa fa-bullhorn',
        [
            [
                '消息列表',
                './@admin/bulletin',
                'fa fa-list'
            ],
            [
                '发送消息',
                './@admin/bulletin/create',
                'fa fa-plus'
            ]
        ]
    ],
    [
        $this->text($user->name),
        false,
        'fa fa-user',
        [
            [
                '个人资料',
                './@admin/account',
                'fa fa-info-circle'
            ],
            [
                '账号关联',
                './@admin/account/connect',
                'fa fa-link'
            ],
            [
                '更改密码',
                './@admin/account/password',
                'fa fa-key'
            ],
            [
                '登陆记录',
                './@admin/account/login_log',
                'fa fa-calendar-alt'
            ],
            [
                '操作记录',
                './@admin/account/log',
                'fa fa-calendar'
            ],
            [
                '退出登陆',
                './logout',
                'fa fa-sign-out-alt'
            ]
        ],
        true
    ]
], $content, $this->title ?? 'ZoDream Account Admin',  $this->renderPart( $this->getCompleteFile('@root/Admin/navDrop.php') )) ?>



