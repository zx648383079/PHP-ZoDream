<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
?>
<?=Form::open($model, './passkey/twofa_save')?>
    <?=Form::text('recovery_code')->value($recovery_code)->label('恢复码')?>

    <p>打开 Authenticator APP进行扫码添加</p>

    <img src="<?=$qr?>" alt="">

    <?=Form::text('twofa_code', true)->label('动态码')?>
<?= Form::close('id') ?>