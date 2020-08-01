<?php
namespace Module\WeChat\Service\Api;

use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Route\Controller\RestController;
use Zodream\Service\Factory;

class Controller extends RestController {

    protected function rules() {
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
            $wid = app('request')->get('wid');
        }
        return $wid;
    }
}