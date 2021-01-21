<?php
declare(strict_types=1);
namespace Service;

use Zodream\Infrastructure\Contracts\Router;
use Zodream\Infrastructure\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->mapWebRoutes($this->app->make(Router::class));
    }

    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => 'Service\Home',
        ], __DIR__.'/routes.php');
    }
}