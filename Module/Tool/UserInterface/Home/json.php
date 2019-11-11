<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'JSON 美化';
?>

<div class="converter-box large-box">
    <div class="input-box">
        <textarea id="input" name="" placeholder="请输入内容"></textarea>
    </div>
    <div class="actions">
        <button data-type="jsonbeautify">美化</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea id="output" name="" placeholder="输出结果"></textarea>
    </div>
</div>
