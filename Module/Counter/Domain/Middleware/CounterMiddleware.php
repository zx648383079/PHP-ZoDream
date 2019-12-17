<?php
namespace Module\Counter\Domain\Middleware;

use Module\Counter\Domain\Events\Visit;
use Module\Counter\Domain\Listeners\VisitListener;
use Zodream\Service\Middleware\MiddlewareInterface;

class CounterMiddleware implements MiddlewareInterface {

    public function handle($payload, callable $next) {
        if (!app('request')->isCli()) {
            event()->listen(Visit::class, VisitListener::class)
                ->dispatch(Visit::createCurrent());
        }
        return $next($payload);
    }
}
