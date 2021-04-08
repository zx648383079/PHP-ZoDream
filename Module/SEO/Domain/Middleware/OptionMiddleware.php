<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Middleware;

use Infrastructure\Bot;
use Module\SEO\Domain\Option;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Service\Middleware\MiddlewareInterface;

class OptionMiddleware implements MiddlewareInterface {

    public function handle(HttpContext $context, callable $next) {
        /** @var Input $request */
        $request = $context['request'];
        if (Bot::disallowSpider($request->server('HTTP_USER_AGENT', '-'))) {
            return response()->statusCode(400)->str('Robots not allowed ');
        }
        $this->gray();
        if (!str_contains($context->path(), 'admin') && Option::value('site_close')) {
            return $this->showClose();
        }
        return $next($context);
    }

    private function showClose() {
        $retry_date = Option::value('site_close_retry');
        $response = response();
        $response->statusCode(503);
        if (!empty($retry_date) && ($retry_time = strtotime($retry_date))) {
            $response->header('Retry-After',
                gmdate('l d F Y H:i:s', $retry_time).' GMT');
        }
        return view('@root/Home/close', ['content' => Option::value('site_close_tip')]);
    }

    private function gray() {
        if (Option::value('site_gray')) {
            $css = <<<CSS
html {
    filter: grayscale(100%);
}
CSS;
            view()->registerCss($css);
        }
    }
}