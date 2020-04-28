<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@forum.css')
    ->registerJsFile([
        '@jquery.upload.min.js',
        '@forum.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";var UPLOAD_URI="/ueditor.php?action=uploadimage";', $this->url('./', false)), View::HTML_HEAD);
?>
<div class="demo-tip"></div>