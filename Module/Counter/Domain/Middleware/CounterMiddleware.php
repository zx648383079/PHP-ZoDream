<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Middleware;

use Module\Counter\Domain\Events\JumpOut;
use Module\Counter\Domain\Events\Visit;
use Module\Counter\Domain\Listeners\JumpOutListener;
use Module\Counter\Domain\Listeners\VisitListener;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Service\Middleware\MiddlewareInterface;
use Zodream\Template\View;

class CounterMiddleware implements MiddlewareInterface {

    public function handle(HttpContext $context, callable $next) {
        $this->boot($context->path());
        return $next($context);
    }

    private function boot(string $path) {
        if (app()->isDebug() || request()->isCli()) {
            return;
        }
        $event = event();
        $event->listen(Visit::class, VisitListener::class)
            ->listen(JumpOut::class, JumpOutListener::class);
        if (str_starts_with($path, 'counter')
            || str_starts_with($path, 'auth')
            || str_starts_with($path, 'to')
            || str_starts_with($path, 'admin.php')
            || strpos($path, 'admin') > 3) {
            return;
        }
        $event->dispatch(Visit::createCurrent());
//        $this->useCounter();
        $this->useGoogle();
        $this->useBaidu();
    }

    private function useGoogle() {
        $key = config('thirdparty.google.analytics');
        if (empty($key)) {
            return;
        }
        $js = <<<JS
window.dataLayer = window.dataLayer || [];
  function gtag(){
      dataLayer.push(arguments);
  }
  gtag('js', new Date());
  gtag('config', '{$key}');
JS;
        view()->registerJsFile('https://www.googletagmanager.com/gtag/js?id='.$key, [
                'position' => View::HTML_HEAD
            ])->registerJs($js, View::HTML_HEAD);
    }

    private function useBaidu() {
        $key = config('thirdparty.baidu.tongji');
        if (empty($key)) {
            return;
        }
        $js = <<<JS
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?{$key}";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
JS;
        view()->registerJs($js, View::HTML_HEAD);
    }

    private function useCounter() {
        $js = <<<JS
var _hmt = _hmt || [];
(function() {
    var hm = document.createElement("script");
    hm.src = '/assets/js/hm.min.js';
    var s = document.getElementsByTagName("script")[0]; 
    s.parentNode.insertBefore(hm, s);
})();
JS;
        view()->registerJs($js, View::HTML_HEAD);
    }
}
