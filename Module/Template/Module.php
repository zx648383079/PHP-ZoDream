<?php
declare(strict_types=1);
namespace Module\Template;

use Module\Template\Domain\Migrations\CreateTemplateTables;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\Controller\ICustomRouteModule;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ICustomRouteModule {

    public function getMigration() {
        return new CreateTemplateTables();
    }


    public function invokeRoute(string $path, HttpContext $context): null|string|Output {
        $path = trim($path, '/');
        if (str_starts_with($path, 'admin') || str_starts_with($path, 'lazy')) {
            return null;
        }
        $host = $context['request']->host();
        try {
            return VisualFactory::cachePage(sprintf('%s/%s', $host, $path),
                function () use ($host, $path) {
                    return VisualFactory::entryRewrite($host, $path);
                });
        } catch (\Exception) {
            return null;
        }
    }
}