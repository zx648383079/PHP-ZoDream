<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$style = <<<CSS
.weight-row {
    min-height: 100px;
}
CSS;
$page->getFactory()->registerCss($style);
?>
<?=$page->template()?>