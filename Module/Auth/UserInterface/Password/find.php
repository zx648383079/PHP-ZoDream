<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */
$this->title = __('Retrieve password');
?>
<section class="container">
    <div class="login-box">
        <form class="form-ico login-form" action="<?= $this->url('./password/send', false) ?>" method="POST">
            <div class="input-group">
                <input type="email" name="email" class="form-control" placeholder="<?=__('Please input email')?>" required>
                <i class="fa fa-at" aria-hidden="true"></i>
            </div>

            <button type="submit" class="btn btn-full"><?=__('Send verification email')?></button>
            <div class="other-box">
                <a href="<?=$this->url('./')?>"><?=__('Back')?></a>
            </div>
            <?= Form::token() ?>
        </form>
        
    </div>
</section>