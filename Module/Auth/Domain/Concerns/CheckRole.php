<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Concerns;

use Zodream\Route\Controller\Controller as RestController;

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
        if ($this instanceof RestController) {
            return $this->renderFailure(__('Not Role!'), 403, 403);
        }
        return $this->redirectWithMessage('/',
            __('Not Role!')
            , 4, 403);
    }
}