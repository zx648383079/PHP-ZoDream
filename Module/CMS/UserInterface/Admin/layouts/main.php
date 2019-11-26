<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$menus = [];
foreach ($cat_menu as $item) {
    $menus[] = [
        sprintf('%s %s', $item['level'] > 0 ? 'ￂ'.
            str_repeat('ｰ', $item['level'] - 1) : '', $item['title']),
        ['./@admin/content', 'cat_id' => $item['id']],
        'fa fa-file'
    ];
}

$form_menu = [];
foreach ($form_list as $item) {
    $form_menu[] = [
        $item['name'],
        ['./@admin/form', 'id' => $item['id']],
        'fa fa-paint-brush'
    ];
}

$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@dialog.css',
        '@zodream-admin.css',
        '@cms_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@cms_admin.min.js'
    ]);
?>

<?= Layout::main($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '模块管理',
        false,
        'fa fa-cubes',
        [
            [
                '栏目',
                './@admin/category',
                'fa fa-bars'
            ],
            [
                '模型',
                './@admin/model',
                'fa fa-boxes'
            ],
            [
                '分组',
                './@admin/group',
                'fa fa-columns'
            ],
            [
                '联动',
                './@admin/linkage',
                'fa fa-cogs'
            ]
        ],
        true
    ],
    [
        'label' => '内容管理',
        'url' => false,
        'icon' => 'fa fa-book',
        'children' => $menus,
        'class' => 'text-left',
        'expand' => url()->hasUri('content')
    ],
    [
        'label' => '表单管理',
        'url' => false,
        'icon' => 'fa fa-book',
        'children' => $form_menu,
        'class' => 'text-left',
        'expand' => url()->hasUri('form')
    ],
    [
        '主题管理',
        false,
        'fa fa-drum',
        [
            [
                '本地主题',
                './@admin/theme',
                'fa fa-desktop'
            ],
            [
                '主题市场',
                './@admin/theme/market',
                'fa fa-shopping-bag'
            ],
        ]
    ]
], $content, 'ZoDream CMS Admin') ?>
