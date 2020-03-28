<?php
namespace Module\Auth\Domain\Exception;

use Exception;

class AuthException {

    public static function invalidLogin() {
        return new Exception(__('Email or password is incorrect'));
    }

    public static function disableAccount() {
        return new Exception(__('Account is disabled'));
    }

    public static function invalidPassword() {
        return new Exception(__('Password is incorrect'));
    }
}