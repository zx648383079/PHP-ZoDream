<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '二/八/十六进制';
?>

<div class="converter-box">
    <div class="input-box">
        <textarea name="" placeholder="请输入内容"></textarea>
    </div>
    <div class="actions" style="padding-top:0">
        <button data-type="binaryencode">二进制编码</button>
        <button data-type="binarydecode">二进制解码</button>
        <button data-type="octalencode">八进制编码</button>
        <button data-type="octaldecode">八进制解码</button>
        <button data-type="hexencode">十六进制编码</button>
        <button data-type="hexdecode">十六进制解码</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea name="" placeholder="输出结果"></textarea>
    </div>
</div>
<div class="converter-tip">
    <p>
    二进制前缀：0b 或字符串 \b
    </p>
    <p>
    八进制前缀：0 或字符串 \
    </p>
    <p>
    二进制前缀：0x 或字符串 \x
    </p>
</div>
