<?php
namespace Module\WeChat\Service\Api;
use Module\ModuleController as RestController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

class Controller extends RestController {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function weChatId($id = null) {
        static $wid;
        if (!is_null($id)) {
            return $wid = $id;
        }
        if (empty($wid)) {
            $wid = request('wid');
        }
        return $wid;
    }
}