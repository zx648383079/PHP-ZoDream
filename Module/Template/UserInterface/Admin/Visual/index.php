<?php
defined('APP_DIR') or exit();

use Module\Template\Domain\VisualEditor\VisualPage;
use Zodream\Template\View;
/** @var $this View */
/** @var $page VisualPage */

$id = $model->id;
$js = <<<JS
bindPage('{$id}');
JS;
$this->registerJsFile([
    '@jquery.min.js',
    '@jquery.dialog.min.js',
    '@jquery.datetimer.min.js',
    '@main.min.js',
    '@template.min.js',
    '@jquery.editor.min.js',
    '@visual_editor.min.js'
])->registerCssFile([
    '@font-awesome.min.css',
    '@zodream.css',
    '@zodream-admin.css',
    '@editor.css',
    '@dialog.css',
    '@template.css',
    '@visual_editor.css',
])->registerJs(sprintf('var BASE_URI="%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./@admin/', false)), View::HTML_HEAD)->registerJs($js, View::JQUERY_READY);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$model->title?></title>
    <?= $this->header() ?>
</head>
<body class="only-editor">
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
<?= $this->footer() ?>
</body>
</html>



