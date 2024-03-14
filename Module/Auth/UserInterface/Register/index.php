<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */
$this->title = __('Sign up');
$js = <<<JS
bindRegister();
JS;

$this->registerJs($js);
?>
<section class="container">
    <div class="login-box">
        <form class="form-ico login-form" action="<?= $this->url('./register/post', false) ?>" method="POST">
        <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="<?=__('Please input nickname')?>" required>
                <i class="fa fa-user" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="email" name="email" class="form-control" placeholder="<?=__('Please input email')?>" required>
                <i class="fa fa-at" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="password" name="password" class="form-control" placeholder="<?=__('Please input the password')?>" required>
                <i class="fa fa-lock" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="password" name="rePassword" class="form-control" placeholder="<?=__('Please confirm your password')?>" required>
                <i class="fa fa-redo" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="text" name="invite_code" class="form-control" placeholder="<?=__('Please input the invite code')?>">
                <i class="fa fa-gift" aria-hidden="true"></i>
            </div>

            <div class="input-group">
                <div class="checkbox">
                    <input type="checkbox" name="agree" value="1" id="checkboxInput"/>
                    <label for="checkboxInput"></label>
                </div>
                <?=__('I agree to')?>《
                <a href="<?=$this->url('/agreement')?>"><?=__('terms of service')?></a>
                》
            </div>

            <button type="submit" class="btn"><?=__('Sign up')?></button>
            <div class="other-box">
                <a href="<?=$this->url('./')?>"><?=__('Back')?></a>
            </div>
            <?= Form::token() ?>
        </form>
        
    </div>
</section>