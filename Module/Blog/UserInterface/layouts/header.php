<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->set([
    'rss_show' => true
])->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@blog.min.css'])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>
<div id="book-page" class="book-skin">