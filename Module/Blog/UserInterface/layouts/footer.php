<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile([
    '@jquery.min.js',
    '@jquery.lazyload.min.js',
    '@blog.min.js']);
?>
        <div class="footer">
            <a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
        </div>
    </div>
<?=$this->footer()?>
</body>
</html>
