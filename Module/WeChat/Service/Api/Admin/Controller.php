<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\WeChat\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

    public function weChatId(int $id = -1) {
        static $wid = 0;
        if ($id > 0) {
            return $wid = $id;
        }
        if ($wid < 1) {
            $wid = intval(request('wid'));
        }
        return $wid;
    }

}