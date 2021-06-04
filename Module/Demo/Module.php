<?php
namespace Module\Demo;

use Module\Demo\Domain\Migrations\CreateDemoTables;
use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Service\PreviewController;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\BoundMethod;
use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Route\Router;
use Module\SEO\Domain\SiteMap;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateDemoTables();
    }

    public function invokeRoute($path, HttpContext $context) {
        if (strpos($path, 'preview') !== 1) {
            return;
        }
        $uri = request()->path();
        if (!preg_match('#preview/view/\d+/id/(\d+)/file/(.*)$#', $uri, $match)) {
            return;
        }
        $args = explode('/', trim($match[2], '/'));
        if (is_numeric($args[0])) {
            array_shift($args);
        }
        $file = implode('/', $args);
        if (empty($file)) {
            return;
        }
        return BoundMethod::call(
            sprintf('%s@%s%s', PreviewController::class, 'view', config('app.action')),
            $context, ['id' => $match[1], 'file' => $file]);
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $items = PostModel::orderBy('id', 'desc')
            ->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./', ['id' => $item['id']]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
    }
}