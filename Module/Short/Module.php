<?php
declare(strict_types=1);
namespace Module\Short;

use Module\Short\Domain\Migrations\CreateShortTables;
use Module\Short\Domain\Repositories\ShortRepository;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\Controller\ICustomRouteModule;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ICustomRouteModule {

    public function getMigration() {
        return new CreateShortTables();
    }

    public function invokeRoute(string $path, HttpContext $context): null|string|Output {
        $path = trim($path, '/');
        if (empty($path)) {
            return null;
        }
        $prefix = explode('/', $path, 2)[0];
        if ($prefix === 'home' || $prefix === 'api') {
            return null;
        }
        $response = $context['response'];
        /** @var Request $request */
        $request = $context['request'];
        try {
            $short = ShortRepository::click($path, $request);
            if (!$short->is_system) {
                return $response->redirect($short->source_url);
            }
            $uri = new Uri($short->source_url);
            $request->append($uri->getData());
            return app()->handle($uri->getPath());
        } catch (\Exception $ex) {
            return $response->redirect(url('/'));
        }
    }
}