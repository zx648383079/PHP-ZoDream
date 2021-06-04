<?php
namespace Module\Short;

use Module\Short\Domain\Migrations\CreateShortTables;
use Module\Short\Domain\Repositories\ShortRepository;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateShortTables();
    }

    public function invokeRoute($path) {
        $path = trim($path, '/');
        if (empty($path)) {
            return;
        }
        if ($path === 'home' || str_starts_with($path, 'home/')) {
            return;
        }
        $response = response();
        /** @var Request $request */
        $request = request();
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