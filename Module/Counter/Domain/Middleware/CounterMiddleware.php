<?php
namespace Module\Counter\Domain\Middleware;

use Module\Counter\Domain\Events\JumpOut;
use Module\Counter\Domain\Events\Visit;
use Module\Counter\Domain\Listeners\JumpOutListener;
use Module\Counter\Domain\Listeners\VisitListener;
use Zodream\Service\Middleware\MiddlewareInterface;
use Zodream\Template\View;

class CounterMiddleware implements MiddlewareInterface {

    public function handle($context, callable $next) {
        $this->boot();
        return $next($context);
    }

    private function boot() {
        if (app()->isDebug() || request()->isCli()) {
            return;
        }
        $event = event();
        $event->listen(Visit::class, VisitListener::class)
            ->listen(JumpOut::class, JumpOutListener::class);
        $uri = request()->path();
        if (str_starts_with($uri, '/counter')
            || str_starts_with($uri, '/auth')
            || str_starts_with($uri, '/to')
            || str_starts_with($uri, '/admin.php')
            || strpos($uri, '/admin') > 3) {
            return;
        }
        $event->dispatch(Visit::createCurrent());
        $this->useBaidu();
    }

    private function useBaidu() {
        $key = config('baidu.tongji');
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
