<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '兽音在线编码解码工具';
$this->keywords = '兽音在线工具,兽音编码解码,兽音转换';
$this->description = 'ZoDream在线工具提供兽音在线工具,兽音编码解码,兽音转换';
$this->registerJsFile([
        '@beast.parser.min.js',
    ]);
?>

<div class="converter-header">
    <label for="keys">加密字典</label>
    <input type="text" class="form-control" id="keys" value="嗷呜啊~">
</div>

<div class="converter-box">
    <div class="input-box">
        <textarea name="" placeholder="请输入内容"></textarea>
    </div>
    <div class="actions">
        <button data-type="beast_encode">编码</button>
        <button data-type="beast_decode">解码</button>
        <button data-type="clear">清空</button>
    </div>
    <div class="output-box">
        <textarea name="" placeholder="输出结果"></textarea>
    </div>
</div>

<div class="converter-tooltip">
    <h3 class="tooltip-header">兽音译者</h3>
    <p>
        兽音译者最早流行于 ACG 与二次元社群，因输出编码由 「嗷、呜、啊、~」四个字符组成、酷似野兽咆哮而得名，本质上是一种可逆的字符替换编码。
    </p>
    <h3 class="tooltip-header">兽音编码原理</h3>
    <ul>
        <li>1. 把每个字符转成 UTF-16BE 字节，例如 a → 00 61。</li>
        <li>2. 将字节流按半字节展开为 hex 序列，得到形如 0061 0061 的字符串。</li>
        <li>3. 每个 hex 字符按其位置 i 做偏移：x = (x + i) mod 16。避免相同明文产生重复片段。</li>
        <li>4. hex（0–F）转为 base-4（每位 → 两个 0–3 数字），并加上前缀 310 与后缀 2。</li>
        <li>5. 把 0/1/2/3 分别替换成 嗷/呜/啊/~。</li>
        <li>6. 加上兽音字符字符签名（~呜嗷[...]啊），就得到最终兽语密文。</li>
    </ul>
</div>
