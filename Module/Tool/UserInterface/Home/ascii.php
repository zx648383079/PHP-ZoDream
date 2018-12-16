<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ASCII编解码';
?>

<div class="converter-box">
    <div class="input-box">
        <textarea name="" placeholder="请输入内容,输入多个ascii码请用非数字字符隔开分隔"></textarea>
    </div>
    <div class="actions">
        <button data-type="ascii_encode">编码</button>
        <button data-type="ascii_decode">解码</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea name="" placeholder="输出结果"></textarea>
    </div>
</div>