<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="column-item">
    <div class="icon">
        <?= $total ?>
    </div>
    <div class="content">
        <h3>总投资额</h3>
    </div>
</div>

<div class="column-item">
    <div class="icon">
        <?= $income ?>
    </div>
    <div class="content">
        <h3>已支付收益</h3>
    </div>
</div>