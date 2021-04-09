<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'CSS 美化/css2scss';
$this->description = 'css beautify or css2scss';
$js = <<<JS
registerEditor('text/css');
JS;
$this->registerJs($js);
?>
<h1>css beautify/css2scss</h1>
<h3>在下面粘贴你的css 代码，然后点击响应按钮“css美化”或“css2scss”，转化结果会马上出现在下面第二个框中。</h3>
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
<div class="converter-tip">
    <div class="tip-header">
        css 转 scss
    </div>
    <p>
        注意： css 转化成 scss, 不会提取颜色字体，尽可能的拆分 css 选择器，然后根据同类进行合并规则，当前并不会进行根据样式合并css选择器。
    </p>
</div>