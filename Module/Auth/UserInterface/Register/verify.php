<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */
$this->title = __('Verify email address');
?>

<div class="container">
    <?php if($message):?>
    <div class="toast-panel">
        <div class="panel-icon">
            <i class="fa fa-times-circle"></i>
        </div>
        <div class="panel-body">
            <?= $message ?>
        </div>
    </div>
    <?php else:?>
    <div class="toast-panel toast-success">
        <div class="panel-icon">
            <i class="fa fa-check-circle"></i>
        </div>
        <div class="panel-body">
        Email验证成功，可以正常登录使用了！
        </div>
    </div>
    <?php endif;?>
</div>