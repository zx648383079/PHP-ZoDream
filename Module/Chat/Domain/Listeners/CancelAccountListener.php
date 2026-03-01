<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Listeners;

use Module\Auth\Domain\Events\CancelAccount;
use Module\Chat\Domain\Model\ChatHistoryModel;
use Module\Chat\Domain\Model\MessageModel;

class CancelAccountListener {

    public function __construct(CancelAccount $event) {
        ChatHistoryModel::where('user_id', $event->getUserId())->delete();
        MessageModel::where('user_id', $event->getUserId())->delete();
    }
}
