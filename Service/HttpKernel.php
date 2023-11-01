<?php
declare(strict_types=1);
namespace Service;

use Module\SEO\Domain\Middleware\OptionMiddleware;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Service\Http\Kernel;
use Zodream\Service\Middleware\CSRFMiddleware;

class HttpKernel extends Kernel {

    public function bootstrap(): void {
        parent::bootstrap();
        /** @var Input $input */
        $input = $this->app->make('request');
        if ($this->isOpenCSRF($input->routePath())) {
            $this->middleware[] = CSRFMiddleware::class;
        }
        $this->middleware[] = OptionMiddleware::class;
        if ($this->app->make('app.module') !== 'Home') {
            return;
        }
        $counterCls = 'Module\Counter\Domain\Middleware\CounterMiddleware';
        if (class_exists($counterCls)) {
            $this->middleware[] = $counterCls;
        }
    }

    protected function isOpenCSRF(string $path): bool {
        if ($this->app->make('app.module') !== 'Home') {
            return true;
        }
        $openPath = 'open'; //$this->getOpenPath();
        return empty($openPath) || !str_starts_with($path, $openPath);
    }

    protected function getOpenPath(): string {
        $cls = 'Module\OpenPlatform';
        $routes = config('route.modules', []);
        foreach ($routes as $path => $module) {
            if (str_starts_with($module, $cls)) {
                return $path;
            }
        }
        return '';
    }
}