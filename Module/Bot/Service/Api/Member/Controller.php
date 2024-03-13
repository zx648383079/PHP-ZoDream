<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Member;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\Bot\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use CheckRole;

    public function rules() {
        return [
            '*' => 'bot_manage'
        ];
    }

    public function weChatId(int $id = -1) {
        static $bot_id = 0;
        if ($id > 0) {
            return $bot_id = $id;
        }
        if ($bot_id < 1) {
            $bot_id = intval(request('bot_id'));
        }
        return $bot_id;
    }

}