<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '微博客';
$js = <<<JS
bindMicroPage();
JS;
$this->registerCssFile([
    '@dialog.css',
    '@animate.min.css',
    '@micro.css'])
    ->registerJsFile([
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@micro.min.js'
    ])
    ->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./', false)), View::HTML_HEAD)
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="micro-skin">
    <?php $this->extend('./publish');?>

    <?php foreach($blog_list as $item):?>
        <?php $this->extend('./item', ['blog' => $item]);?>
    <?php endforeach;?>
</div>