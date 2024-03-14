<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
use Module\Auth\Domain\Repositories\AuthRepository;
/** @var $this View */
$this->title = __('Sign in');
$passkeyUri = $this->url('./', false);
$js = <<<JS
bindLogin('{$passkeyUri}');
JS;
$this->registerJs($js);
?>
<section class="container">
    <div class="login-box">
        <form class="form-ico login-form" action="<?= $this->url('./home/login', false) ?>" method="POST">
            <div class="input-box">
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="<?=__('Please input username')?>" required autocomplete="off">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" placeholder="<?=__('Please input the password')?>" required autocomplete="off">
                    <i class="fa fa-lock" aria-hidden="true" ></i>
                </div>
                <div class="input-group 2fa-input" style="display:none">
                    <input type="text" name="twofa_code" class="form-control" placeholder="<?=__('Please input authentication code')?>" autocomplete="off">
                    <i class="fa fa-mobile" aria-hidden="true" ></i>
                </div>
                <div class="input-group captcha-input" <?=$isCaptcha ? '' : 'style="display:none"'?>>
                    <input type="text" name="captcha" class="form-control" placeholder="<?=__('Please enter verification code')?>" autocomplete="off">
                    <a href="javascript:;" class="btn" title="<?=__('Click refresh')?>">
                        <img src="<?= $isCaptcha ? $this->url('./captcha', ['v' => time()], false) : ''?>" data-src="<?=$this->url('./captcha?', false)?>" alt="<?=__('Refresh and retry')?>">
                    </a>
                    <i class="fa fa-key" aria-hidden="true" ></i>
                </div>

                <div class="input-group">
                    <div class="checkbox">
                        <input type="checkbox" name="rememberMe" value="1" id="checkboxInput"/>
                        <label for="checkboxInput"></label>
                    </div>
                    <?=__('Remember')?>
                    <a class="find-link" href="<?= $this->url('./password/find') ?>"><?=__('Forgot your password?')?></a>
                </div>

                <button type="submit" class="btn"><?=__('Sign in')?></button>
                <div class="other-box">
                    <a href="<?= $this->url('./register') ?>"><?=__('Sign up')?></a>
                    <i class="fa fa-qrcode"></i>
                </div>

                <input type="hidden" name="redirect_uri" value="<?= $redirect_uri ?>">
            </div>
            <div class="login-qr-box">
                <div class="qr-box">
                    <img lazy-src="<?=$this->url('./qr')?>" alt="">
                    <i class="fa fa-sync"></i>
                </div>
                <div class="scan-box">
                    <i class="fa fa-check-circle"></i>
                    <p><?=__('Scanning is successful, waiting for confirmation')?></p>
                </div>
                <div class="success-box">
                    <i class="fa fa-check-circle"></i>
                    <p><?=__('Sign in successfully')?></p>
                </div>
                <div class="failure-box">
                    <i class="fa fa-sync"></i>
                    <p><?=__('Sign in failed')?></p>
                </div>
                <button type="button" class="btn"><?=__('Back')?></button>
            </div>

            <?= Form::token() ?>
        </form>
        <?php if(AuthRepository::openOAuth()):?>
        <div class="login-oauth-box">
            <div class="box-title"><?=__('Sign in with')?></div>
            <a href="<?=$this->url('./oauth', ['type' => 'qq', 'redirect_uri' => $redirect_uri], false)?>" title="<?=__('Sign in with QQ')?>"><i class="fab fa-qq"></i></a>
            <a href="<?=$this->url('./oauth', ['type' => 'wechat', 'redirect_uri' => $redirect_uri], false)?>" title="<?=__('Sign in with WeChat')?>"><i class="fab fa-weixin"></i></a>
            <a href="<?=$this->url('./oauth', ['type' => 'weibo', 'redirect_uri' => $redirect_uri], false)?>" title="<?=__('Sign in with Weibo')?>"><i class="fab fa-weibo"></i></a>
            <a href="<?=$this->url('./oauth', ['type' => 'github', 'redirect_uri' => $redirect_uri], false)?>" title="<?=__('Sign in with Github')?>"><i class="fab fa-github"></i></a>
            <a class="login-webauth" href="javascript:;" title="<?=__('Sign in with WebAuthn')?>">
                <i class="fa fa-fingerprint"></i>
            </a>
        </div>
        <?php endif;?>
    </div>
</section>