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
            './admin',
            'fa fa-home',
        ],
        [
            '项目列表',
            false,
            'fa fa-money-bill',
            [

            ],
            true
        ]
    ];
    foreach ($project_list as $item) {
        $menus[1][3][] = [
            $item['name'],
            ['./admin/project', 'id' => $item['id']],
            'fa fa-book'
        ];
    }
    $menus[1][3][] = [
        '新建项目',
        './admin/project/create',
        'fa fa-plus'
    ];
} else {
    $menus = [
        [
            '返回首页',
            './admin',
            'fa fa-arrow-left',
        ],
        [
            '项目主页',
            ['./admin/project', 'id' => $project->id],
            'fa fa-home',
        ]
    ];
    $baseUri = $project->type == 1 ? './admin/api' : './admin/page';
    foreach ($tree_list as $item) {
        $children = [];
        if(isset($item['children'])) {
            foreach($item['children'] as $child) {
                $children[] = [
                    $child['name'],
                    [$baseUri, 'id' => $child['id']],
                    'fa fa-file'
                ];
            }
        }
        $children[] = [
            $project->type == 1 ? '新建接口' : '新建文档',
            [$baseUri.'/create', 'project_id' => $project->id, 'parent_id' => $item['id']],
            'fa fa-plus'
        ];
        $menus[] = [
            $item['name'],
            false,
            'fa fa-folder',
            $children,
            true
        ];
    }
    $menus[] = [
        '新建模块',
        [$baseUri.'/create', 'project_id' => $project->id],
        'fa fa-plus'
    ];
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
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@clipboard.min.js',
        '@prism.js',
        '@main.min.js',
        '@doc.min.js'
    ]);
?>

<?= Layout::main($this, $menus, $content, 'ZoDream Document Admin') ?>
