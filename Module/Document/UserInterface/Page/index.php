<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '文档：'.$model->name;
?>

<div class="row">
    <div class="col-lg-12">
        <?=$model->content?>
    </div>
</div>
