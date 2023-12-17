<?php
defined('APP_DIR') or exit();

use Zodream\Template\View;
/** @var $this View */
/** @var $page VisualPage */

$this->title = $model->title;

$id = $model->id;
$js = <<<JS
bindPage('{$id}');
JS;
$this->registerJsFile('@visual_control.min.js', ['defer' => true, 'position' => View::HTML_HEAD])->registerJsFile([
    '@jquery.editor.min.js',
    '@visual_editor.min.js'
])->registerCssFile([
    '@editor.min.css',
    '@visual_editor.min.css'
])->registerJs($js, View::JQUERY_READY);
?>

<nav class="top-nav">
    <ul class="navbar">
        <li>
            <div>
                <i class="fa fa-mobile"></i>
                <span>屏幕</span>
            </div>
            <ul class="down mobile-size">
                <li data-size="320*568"><i class="fa fa-mobile"></i>iPhone 5</li>
                <li data-size="360*640"><i class="fa fa-mobile"></i>Galaxy S5</li>
                <li data-size="370*640"><i class="fa fa-mobile"></i>Lumia 650</li>
                <li data-size="375*812"><i class="fa fa-mobile"></i>iPhone X</li>
                <li data-size="412*732"><i class="fa fa-mobile"></i>Nexus 5X</li>
                <li data-size="414*736"><i class="fa fa-mobile"></i>iPhone 6 Plus</li>
                <li data-size="768*1024"><i class="fa fa-tablet"></i>iPad</li>
                <li data-size="*"><i class="fa fa-desktop"></i>全屏</li>
            </ul>
        </li>
        <li>
            <div class="mobile-rotate">
                <i class="fa fa-undo"></i>
                <span>旋转</span>
            </div>
        </li>
        <li>
            <a data-type="ajax" href="<?=$this->url('./@admin/theme/install')?>">
                <i class="fa fa-sync"></i>
                <span>刷新</span>
            </a>
        </li>
    </ul>
</nav>
<div id="page-box">
    
</div>

