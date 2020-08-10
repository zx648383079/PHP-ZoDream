<?php
namespace Module\SEO\Domain\Middleware;

use Module\SEO\Domain\Option;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Http\Response;
use Zodream\Service\Middleware\MiddlewareInterface;

class OptionMiddleware implements MiddlewareInterface {

    public function handle($payload, callable $next) {
        $this->gray();
        if (strpos($payload, 'admin') === false && Option::value('site_close')) {
            return $this->showClose();
        }
        return $next($payload);
    }

    private function showClose() {
        $retry_date = Option::value('site_close_retry');
        /** @var Response $response */
        $response = app('response');
        $response->setStatusCode(503);
        if (!empty($retry_date) && ($retry_time = strtotime($retry_date))) {
            $response->header->set('Retry-After',
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