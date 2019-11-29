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
            'fa fa-money-bill',
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
    $baseUri = $project->type == 1 ? './api' : './page';
    foreach ($tree_list as $key => $item) {
        $children = [];
        $has_active = false;
        if(isset($item['children'])) {
            foreach($item['children'] as $child) {
                $is_active = isset($current_id) && $child['id'] == $current_id;
                $children[] = [
                    $child['name'],
                    [$baseUri, 'id' => $child['id']],
                    'fa fa-file',
                    null,
                    false,
                    $is_active
                ];
                if ($is_active) {
                    $has_active = true;
                }
            }
        }
        $menus[] = [
            $item['name'],
            false,
            'fa fa-folder',
            $children,
            (!isset($current_id) && $key < 1) || (isset($current_id) && $has_active)
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
    ])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>

<?= Layout::main($this, $menus, $content, 'ZoDream Document') ?>
