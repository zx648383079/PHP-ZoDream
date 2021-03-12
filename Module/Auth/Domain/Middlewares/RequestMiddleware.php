<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Middlewares;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\Controller\Middleware\RequestMiddleware as BaseMiddleware;

class RequestMiddleware extends BaseMiddleware {

    protected function processCustomRule(string $role, HttpContext $context)
    {
        if (auth()->guest()) {
            return $this->renderRedirectAuth($context);
        }
        /** @var UserModel $user */
        $user = auth()->user();
        if (!$user->isAdministrator() && !$user->hasRole($role)) {
            return $context['controller']->redirectWithMessage('./', '无权限访问');
        }
        return parent::processCustomRule($role, $context);
    }
}