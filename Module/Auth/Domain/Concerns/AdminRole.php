<?php
namespace Module\Auth\Domain\Concerns;

/**
 * 管理员权限控制器
 * @package Module\Auth\Domain\Concerns
 */
trait AdminRole {
    protected function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    protected function processCustomRule($role) {
        if (auth()->guest()) {
            return $this->redirectWithAuth();
        }
        if (auth()->user()->hasRole($role)) {
            return true;
        }
        return $this->redirectWithMessage('/',
            __('Not Role！')
            , 4,403);
    }
}