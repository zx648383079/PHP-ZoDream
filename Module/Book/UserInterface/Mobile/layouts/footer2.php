<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
      ->registerJsFile('@jquery.cookie.js')
      ->registerJsFile('@jquery.dialog.min.js')
      ->registerJsFile('@book_mobile.min.js');
?>
   <?=$this->footer()?>
   </body>
</html>