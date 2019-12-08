<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@login.min.js');
?>
<div class="footer">
    <a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
</div>
<?=$this->footer()?>
</body>
</html>
