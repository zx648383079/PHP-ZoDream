<?php
namespace Module;

use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Helpers\Json;
use Zodream\Route\Controller\Controller as BaseController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

abstract class ModuleController extends BaseController {

    public function __construct()
    {
        $this->middleware(RequestMiddleware::class);
    }

    public function getConfig() {
        return [];
    }

    public function setConfig($data) {

    }

    protected function checkUser() {
        if (!auth()->guest()) {
            return true;
        }
        if (AuthRepository::loginByBasicAuthorization()) {
            return true;
        }
        return false;
    }

    public static function parseArrInt($selected) {
        if (!empty($selected) && is_string($selected)) {
            if (strpos($selected, '[') === false) {
                $selected = explode(',', $selected);
            } else {
                $selected = Json::decode($selected, true);
            }
        }
        $data = [];
        foreach ((array)$selected as $item) {
            $item = intval($item);
            if ($item < 1) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

    /**
     * 拆分两个数组
     * @param array $current
     * @param array $exist
     * @return array
     */
    public static function splitId($current, $exist) {
        if (empty($exist) && empty($current)) {
            return [[], [], []];
        }
        if (empty($exist)) {
            return [$current, [], []];
        }
        if (empty($current)) {
            return [[], [], $exist];
        }
        return [array_diff($current, $exist), array_intersect($current, $exist), array_diff($exist, $current)];
    }

    /**
     * 将多项表单转为数组
     * @param array $data
     * @param null $default
     * @param callable|null $check
     * @return array
     */
    public static function formArr(array $data, $default = null, callable $check = null) {
        if (empty($data)) {
            return [];
        }
        $items = [];
        $first = current($data);
        if (!is_array($first)) {
            return [];
        }
        foreach ($first as $i => $_) {
            $item = [];
            foreach ($data as $key => $args) {
                $item[$key] = isset($args[$i]) ? $args[$i] : $default;
            }
            if (!empty($check) && call_user_func($check, $item) === false) {
                continue;
            }
            $items[] = $item;
        }
        return $items;
    }


}