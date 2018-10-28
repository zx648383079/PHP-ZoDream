<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '文档：'.$api->name;
?>

<div class="row">
    <div class="col-lg-12">
        <div >
            <h1> <?=$this->title?></h1>
        </div>
    </div>
    <div class="col-lg-12">
        <?=$model->content?>
    </div>
</div>
