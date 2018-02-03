<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@bootstrap.min.js')
    ->registerJsFile('@Validform_v5.3.2_min.js');
?>
   <?=$this->footer()?>
   </body>
</html>