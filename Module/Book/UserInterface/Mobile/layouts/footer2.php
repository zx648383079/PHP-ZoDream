<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
      ->registerJsFile('@jquery.cookie.js')
      ->registerJsFile('@jquery.dialog.min.js')
      ->registerJsFile('@book_mobile.min.js');
?>
<?php if(!app()->isDebug()):?>
        <div class="demo-tip"></div>
    <?php endif;?>
   <?=$this->footer()?>
   </body>
</html>