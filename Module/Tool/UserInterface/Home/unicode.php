<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'Unicode编码转换';
?>

<div class="converter-box">
    <div class="input-box">
        <textarea name="" placeholder="请输入内容"></textarea>
    </div>
    <div class="actions">
        <button data-type="unicode">编码</button>
        <button data-type="deunicode">解码</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea name="" placeholder="输出结果"></textarea>
    </div>
</div>
