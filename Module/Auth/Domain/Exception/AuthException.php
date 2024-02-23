<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Exception;

use Exception;

class AuthException {
    public static function invalid2FA() {
        return new Exception(__('2FA authentication code error'), 1015);
    }
    public static function ipDisallow() {
        return new Exception(__('Too many failures'), 1011);
    }

    public static function accountDisallow() {
        return new Exception(__('Too many failures'), 1010);
    }

    public static function invalidCaptcha() {
        return new Exception(__('captcha error'), 1009);
    }

    public static function invalidLogin() {
        return new Exception(__('Email or password is incorrect'), 1003);
    }

    public static function disableRegister() {
        return new Exception(__('Registration not allowed'), 1006);
    }

    public static function disableAccount() {
        return new Exception(__('Account is disabled'), 1004);
    }

    public static function invalidPassword() {
        return new Exception(__('Password is incorrect'), 1005);
    }

    public static function samePassword() {
        return new Exception(__('Consistent with the original password'), 1007);
    }
}