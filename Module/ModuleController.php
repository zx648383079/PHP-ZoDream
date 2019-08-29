<?php
namespace Module;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Route\Controller\ModuleController as BaseController;

abstract class ModuleController extends BaseController {

    public function getConfig() {
        return [];
    }

    public function setConfig($data) {

    }

    protected function checkUser() {
        if (!auth()->guest()) {
            return true;
        }
        $user = new UserModel();
        if ($user->signInHeader()) {
            return true;
        }
        return false;
    }

    public static function parseArrInt($selected) {
        if (!empty($selected) && is_string($selected)) {
            if (strpos($selected, '[') === false) {
                $selected = explode(',', $selected);
            } else {
                $selected = json_decode($selected, true);
            }
        }
        return array_map('intval', (array)$selected);
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


}