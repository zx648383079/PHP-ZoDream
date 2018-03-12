<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
      ->registerJsFile('@book.min.js');
?>
   <?=$this->footer()?>
   </body>
</html>