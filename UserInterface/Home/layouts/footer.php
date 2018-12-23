<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile([
    '@jquery.min.js',
    '@jquery.lazyload.min.js',
    '@christmas.min.js',
    '@blog.min.js']);
?>
    <div class="footer text-center">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
    </div>
    <canvas id="christmas-box"></canvas>
    <?=$this->footer()?>
</body>
</html>