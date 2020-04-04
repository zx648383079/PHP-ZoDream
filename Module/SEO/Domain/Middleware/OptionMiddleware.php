<?php
namespace Module\SEO\Domain\Middleware;

use Module\SEO\Domain\Option;
use Zodream\Service\Middleware\MiddlewareInterface;

class OptionMiddleware implements MiddlewareInterface {

    public function handle($payload, callable $next) {
        if (Option::value('site_gray')) {
            $css = <<<CSS
html {
    filter: grayscale(100%);
}
CSS;
            view()->registerCss($css);
        }
        return $next($payload);
    }
}