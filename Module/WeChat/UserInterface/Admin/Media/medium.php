<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('editor/medium-editor.min.js')
    ->registerCssFile('editor/medium-editor.min.css')
    ->registerCssFile('editor/default.min.css');
?>

<div id="container"><?=$model->content?></div>
<div class="editor-footer">
    <i class="fa fa-code"></i>
    <i class="fa fa-plus"></i>
</div>