<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service\Api;

use Zodream\Helpers\Arr;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\ModuleRoute;
use Zodream\Route\Response\RestResponse;

class BatchController extends Controller {

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction(Input $input) {
        $params = $input->all();
        $groups = [];
        foreach ($params as $path => $data) {
            $i = strpos($path, '_');
            if ($i === false || $i < 1) {
                continue;
            }
            $groups[substr($path, 0, $i)][substr($path, $i + 1)] = $data;
        }
        $resItems = [];
        foreach ($groups as $module => $items) {
            $res = $this->invokeBatchController($module, $items, $input);// TODO 执行 module 下的 batch 控制控制器，获得结果
            foreach ($res as $path => $data) {
                $resItems[sprintf('%s_%s', $module, $path)] = $data;
            }
        }
        $input->replace($params);
        return $this->render($resItems);
    }

    protected function invokeBatchController(string $path, array $params, Input $input): array {
        /** @var HttpContext $context */
        $context = $this->httpContext();
        /** @var ModuleRoute $route */
        $route = $context->make(ModuleRoute::class);
        $input->replace($params);
        try {
            list($path, $modulePath, $module) = $route->tryMatchModule($path);
            $context['module_path'] = $modulePath;
            $data = $route->invokeModule(sprintf('%s/batch', !empty($path) ? 'api/' . $path : 'api'), $module, $context);
            if ($data instanceof RestResponse) {
                return $data->getData();
            }
            return Arr::toArray($data);
        } catch (\Exception) {
            return [];
        }
    }
}