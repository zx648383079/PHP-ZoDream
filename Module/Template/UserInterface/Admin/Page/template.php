<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$style = <<<CSS
.weight-row {
    min-height: 100px;
}
CSS;
$this->registerCss($style);
?>
<?=$page->template()?>