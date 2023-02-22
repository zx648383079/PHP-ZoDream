<?php
declare(strict_types=1);
namespace Module\Template;

use Module\Template\Domain\Migrations\CreateTemplateTables;
use Module\Template\Domain\VisualEditor\VisualPage;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\Controller\ICustomRouteModule;
use Zodream\Route\Controller\Module as BaseModule;

/**
 * TODO 可视化开发
 */
class Module extends BaseModule implements ICustomRouteModule {

    public function getMigration() {
        return new CreateTemplateTables();
    }


    public function invokeRoute(string $path, HttpContext $context): null|string|Output {
        $path = trim($path, '/');
        if (str_starts_with($path, 'admin') || str_starts_with($path, 'lazy')) {
            return null;
        }
        try {
            $renderer = VisualPage::entryRewrite($context['request']->host(), $path);
        } catch (\Exception) {
            return null;
        }
        return $context['response']->html($renderer->render());
    }
}