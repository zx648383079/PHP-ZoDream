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
} elseif (isset($project)) {
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
                    [],
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
        '@prism.min.css',
        '@zodream.min.css',
        '@zodream-admin.min.css',
        '@dialog.min.css',
        '@doc.min.css'
    ])->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@clipboard.min.js',
        '@jquery.dialog.min.js',
        '@prism.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@doc.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>

<?= Layout::main($this, $menus, $this->contents(), $this->title ?? 'ZoDream Document') ?>
