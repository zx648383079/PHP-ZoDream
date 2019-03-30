<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->content = $content;
$menus = [];
foreach ($cat_menu as $item) {
    $menus[] = [
        sprintf('%s %s', $item['level'] > 0 ? 'ￂ'.
            str_repeat('ｰ', $item['level'] - 1) : '', $item['title']),
        ['./admin/content', 'cat_id' => $item['id']],
        'fa fa-file'
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
        './admin',
        'fa fa-home',
    ],
    [
        '模块管理',
        false,
        'fa fa-cubes',
        [
            [
                '栏目',
                './admin/category',
                'fa fa-bars'
            ],
            [
                '模型',
                './admin/model',
                'fa fa-boxes'
            ],
            [
                '分组',
                './admin/group',
                'fa fa-columns'
            ],
            [
                '联动',
                './admin/linkage',
                'fa fa-cogs'
            ]
        ],
        true
    ],
    [
        '内容管理',
        false,
        'fa fa-book',
        $menus
    ]
], 'ZoDream CMS Admin') ?>
