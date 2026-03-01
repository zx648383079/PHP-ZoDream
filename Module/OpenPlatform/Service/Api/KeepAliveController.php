<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service\Api;

use Domain\Heartbeat;
use Zodream\Route\ModuleRoute;
use Zodream\Infrastructure\Contracts\HttpContext as HttpContextInterface;

class KeepAliveController extends Controller {

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction(int $delta) {
        $modules = config('route.modules', []);
        $res = [];
        foreach ($modules as $module) {
            $instance = ModuleRoute::moduleInstance($module, app(HttpContextInterface::class));
            if (!$instance instanceof Heartbeat) {
                continue;
            }
            $data = $instance->pulse($delta);
            if (!empty($data)) {
                $res = array_merge($res, $data);
            }
        }
        return $this->render($res);
    }
}