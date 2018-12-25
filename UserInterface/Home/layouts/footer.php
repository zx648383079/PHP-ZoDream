<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile([
    '@jquery.min.js',
    '@jquery.lazyload.min.js']);
?>
    <footer>
        <div class="container">
            <?=$this->node('friend-link')?>
            <div class="copyright">
                <a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
            </div>
        </div>
    </footer>
    <?=$this->footer()?>
</body>
</html>