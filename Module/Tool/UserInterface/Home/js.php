<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'JS 美化';
$js = <<<JS
registerEditor('text/javascript');
JS;
$this->registerJs($js);
?>

<div class="converter-box large-box">
    <div class="input-box">
        <textarea id="input" name="" placeholder="请输入内容"></textarea>
    </div>
    <div class="actions">
        <button data-type="jsbeautify">美化</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea id="output" name="" placeholder="输出结果"></textarea>
    </div>
</div>
