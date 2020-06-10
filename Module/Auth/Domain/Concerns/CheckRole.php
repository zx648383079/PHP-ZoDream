<?php
namespace Module\Auth\Domain\Concerns;

/**
 * 验证权限
 * @package Module\Auth\Domain\Concerns
 */
trait CheckRole {

    protected function processCustomRule($role) {
        if (auth()->guest()) {
            return $this->redirectWithAuth();
        }
        if (auth()->user()->isAdministrator() || auth()->user()->hasRole($role)) {
            return true;
        }
        return $this->redirectWithMessage('/',
            __('Not Role!')
            , 4, 403);
    }
}