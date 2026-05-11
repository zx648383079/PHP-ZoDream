<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Middleware;

use Infrastructure\Bot;
use Module\SEO\Domain\Option;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Service\Middleware\MiddlewareInterface;

class OptionMiddleware implements MiddlewareInterface {

    const SafetyVerifyKey = '__sfp';

    public function handle(HttpContext $context, callable $next) {
        /** @var Input $request */
        $request = $context['request'];
        if (Bot::disallowSpider($request->server('HTTP_USER_AGENT', '-'))) {
            return response()->statusCode(400)->str('Robots not allowed ');
        }
        if (!str_contains($context->path(), 'admin') && Option::value('site_close')) {
            return $this->outputClose($context);
        }
        if ($this->isSafetyVerify($context)) {
            return $this->outputSafety($context);
        }
        $this->gray($context);
        return $next($context);
    }

    private function outputClose(HttpContext $context) {
        $retry_date = Option::value('site_close_retry');
        $response = $context['response'];
        $response->statusCode(503);
        if (!empty($retry_date) && ($retry_time = strtotime($retry_date))) {
            $response->header('Retry-After',
                gmdate('l d F Y H:i:s', $retry_time).' GMT');
        }
        return view('@root/Home/close', ['content' => Option::value('site_close_tip')]);
    }


    private function isSafetyVerify(HttpContext $context): bool {
        $option = Option::value('safety_verify');
        if (empty($option)) {
            return false;
        }
        if (session()->has(static::SafetyVerifyKey)) {
            return false;
        }
        return !str_contains($context->path(), 'seo/home/safety');
    }

    private function outputSafety(HttpContext $context) {
        $request = $context['request'];
        $response = response();
        if ($request->expectsJson()) {
            return $response->statusCode(401)->json([
                'code' => '4401',
                'message' => 'Failed security verification',
            ]);
        }
        return view('@root/Home/safety');
    }

    private function gray(HttpContext $context) {
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