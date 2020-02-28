<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '代码段';
$js = <<<JS
bindCode();
JS;
$this->registerCssFile([
    '@dialog.css',
    '@animate.min.css',
    '@prism.css',
    '@micro.css'])
    ->registerJsFile([
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@prism.js',
        '@main.min.js',
        '@micro.min.js'
    ])
    ->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./', false)), View::HTML_HEAD)
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="micro-skin code-container">
    <?php if(!auth()->guest()):?>
        <?php $this->extend('./publish');?>
    <?php endif;?>

    <?php foreach($code_list as $item):?>
        <?php $this->extend('./item', ['code' => $item]);?>
    <?php endforeach;?>

    <div class="micro-footer">
        <?=$code_list->getLink()?>
    </div>
</div>