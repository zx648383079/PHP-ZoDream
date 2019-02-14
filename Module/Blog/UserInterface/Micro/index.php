<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '微博客';
$url = $this->url('./', false);
$js = <<<JS
bindMicroPage('{$url}');
JS;
$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@blog.css'])
    ->registerJsFile([
        '@jquery.min.js',
        '@blog.min.js'
    ])
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="micro-skin">
    <?php $this->extend('./publish');?>

    <?php foreach($blog_list as $item):?>
        <?php $this->extend('./item', ['blog' => $item]);?>
    <?php endforeach;?>
</div>