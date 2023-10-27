<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;

/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@datetimer.css',
        '@dialog.css',
        '@exam.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.pjax.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@jquery.datetimer.min.js',
        '@main.min.js',
        '@admin.min.js',
        '@exam.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD);

?>


<?= Layout::mainIfPjax($this, [
    [
        '首页',
        './@admin',
        'fa fa-home',
    ],
    [
        '题库管理',
        false,
        'fa fa-question',
        [
            [
                '题目列表',
                './@admin/question',
                'fa fa-list'
            ],
            [
                '新增题目',
                './@admin/question/create',
                'fa fa-plus'
            ],
        ]
    ],
    [
        '科目管理',
        false,
        'fa fa-tags',
        [
            [
                '科目列表',
                './@admin/course',
                'fa fa-list'
            ],
            [
                '新增分类',
                './@admin/course/create',
                'fa fa-plus'
            ],
        ]
    ],
    [
        '试卷管理',
        false,
        'fa fa-book',
        [
            [
                '试卷列表',
                './@admin/page',
                'fa fa-list'
            ],
            [
                '新增试卷',
                './@admin/page/create',
                'fa fa-plus'
            ],
        ]
    ],
], $this->contents(), 'ZoDream Exam Admin') ?>
