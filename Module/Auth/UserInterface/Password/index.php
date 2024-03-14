<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */
$this->title = __('Retrieve password');
?>
<section class="container">
    <div class="login-box">
        <form class="form-ico login-form" action="<?= $this->url('./password/reset', false) ?>" method="POST">
            <div class="input-group">
                <input type="email" class="form-control" placeholder="<?=__('Please input email')?>" name="email" required>
                <i class="fa fa-at" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="password" class="form-control" placeholder="<?=__('Please input the password')?>" name="password" required>
                <i class="fa fa-lock" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="password" class="form-control" placeholder="<?=__('Please confirm your password')?>" name="rePassword" required>
                <i class="fa fa-circle" aria-hidden="true"></i>
            </div>

            <button type="submit" class="btn btn-full"><?=__('Reset Password')?></button>
            <input type="hidden" name="code" value="<?=$code?>">
            <?= Form::token() ?>
        </form>
        
    </div>
</section>