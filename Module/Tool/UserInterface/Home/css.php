<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'CSS 美化/转SCSS';
$js = <<<JS
registerEditor('text/css');
JS;
$this->registerJs($js);
?>

<div class="converter-box large-box">
    <div class="input-box">
        <textarea id="input" name="" placeholder="请输入内容"></textarea>
    </div>
    <div class="actions">
        <button data-type="cssbeautify">美化</button>
        <button data-type="csstoscss">转SCSS</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea id="output" name="" placeholder="输出结果"></textarea>
    </div>
</div>
