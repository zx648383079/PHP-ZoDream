<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */

$menus = [];
if(isset($project_list)) {
    $menus = [
        [
            '首页',
            './',
            'fa fa-home',
        ],
        [
            '项目列表',
            false,
            'fa fa-money',
            [

            ],
            true
        ]
    ];
    foreach ($project_list as $item) {
        $menus[1][3][] = [
            $item['name'],
            ['./project', 'id' => $item['id']],
            'fa fa-book'
        ];
    }
} else {
    $menus = [
        [
            '返回首页',
            './',
            'fa fa-arrow-left',
        ],
        [
            '项目主页',
            ['./project', 'id' => $project->id],
            'fa fa-home',
        ]
    ];
    foreach ($tree_list as $key => $item) {
        $children = [];
        if(isset($item['children'])) {
            foreach($item['children'] as $child) {
                $children[] = [
                    $child['name'],
                    ['./api', 'id' => $child['id']],
                    'fa fa-file'
                ];
            }
        }
        $menus[] = [
            $item['name'],
            false,
            'fa fa-folder',
            $children,
            $key < 1
        ];
    }
}

$this->registerCssFile([
        '@font-awesome.min.css',
        '@prism.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@doc.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@clipboard.min.js',
        '@jquery.dialog.min.js',
        '@prism.js',
        '@main.min.js',
        '@doc.min.js'
    ]);
?>

<?= Layout::main($this, $menus, 'ZoDream Document') ?>
