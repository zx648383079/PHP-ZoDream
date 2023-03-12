<?php
declare(strict_types=1);
namespace Module\ResourceStore;

use Module\ResourceStore\Domain\Migrations\CreateResourceTables;
use Module\ResourceStore\Domain\Models\ResourceModel;
use Module\ResourceStore\Service\PreviewController;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\BoundMethod;
use Zodream\Route\Controller\ICustomRouteModule;
use Zodream\Route\Controller\Module as BaseModule;
use Module\SEO\Domain\SiteMap;

class Module extends BaseModule implements ICustomRouteModule {

    public function getMigration() {
        return new CreateResourceTables();
    }

    public function invokeRoute(string $path, HttpContext $context): null|string|Output {
        if (!str_starts_with($path, 'preview')) {
            return null;
        }
        $uri = $context['request']->path();
        if (!preg_match('#preview/view/\d+/id/(\d+)/file/(.*)$#', $uri, $match)) {
            return null;
        }
        $args = explode('/', trim($match[2], '/'));
        if (is_numeric($args[0])) {
            array_shift($args);
        }
        $file = implode('/', $args);
        if (empty($file)) {
            return null;
        }
        return BoundMethod::call(
            sprintf('%s@%s%s', PreviewController::class, 'view', config('app.action')),
            $context, ['id' => $match[1], 'file' => $file]);
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $items = ResourceModel::orderBy('id', 'desc')
            ->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./', ['id' => $item['id']]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
    }
}