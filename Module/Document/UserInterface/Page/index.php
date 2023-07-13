<?php
defined('APP_DIR') or exit();

use Zodream\Html\MarkDown;
use Zodream\Template\View;
/** @var $this View */
$this->title = '文档：'.$model->name;
$this->set('current_id', $model->id);
?>

<div class="row">
    <div class="col-lg-12">
        <h1> <?=$this->title?></h1>
    </div>
    <div class="col-lg-12 style-type-1">
        <?=MarkDown::parse((string)$model->content, true)?>
    </div>
</div>
