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
            './@admin',
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
            ['./@admin/project', 'id' => $item['id']],
            'fa fa-book'
        ];
    }
    $menus[1][3][] = [
        '新建项目',
        './@admin/project/create',
        'fa fa-plus'
    ];
} elseif (isset($project)) {
    $menus = [
        [
            '返回首页',
            './@admin',
            'fa fa-arrow-left',
        ],
        [
            '项目主页',
            ['./@admin/project', 'id' => $project->id],
            'fa fa-home',
        ]
    ];
    $baseUri = $project->type == 1 ? './@admin/api' : './@admin/page';
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
        '@prism.min.css',
        '@zodream.min.css',
        '@zodream-admin.min.css',
        '@dialog.min.css',
        '@doc.min.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@js.cookie.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@clipboard.min.js',
        '@prism.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@doc.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);
?>

<?= Layout::main($this, $menus, $this->contents(), 'ZoDream Document Admin') ?>
