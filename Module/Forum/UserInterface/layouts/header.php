<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js')->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>