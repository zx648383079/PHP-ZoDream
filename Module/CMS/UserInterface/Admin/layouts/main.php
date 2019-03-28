<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->content = $content;
$menus = [];
foreach ($cat_menu as $item) {
    $menus[] = [
        $item->title,
        ['./admin/content', 'cat_id' => $item->id],
        'fa fa-image'
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
                'fa fa-image'
            ],
            [
                '模型',
                './admin/model',
                'fa fa-image'
            ],
            [
                '联动',
                './admin/linkage',
                'fa fa-link'
            ]
        ],
        true
    ],
    [
        '内容管理',
        false,
        'fa fa-file',
        $menus
    ]
], 'ZoDream CMS Admin') ?>
