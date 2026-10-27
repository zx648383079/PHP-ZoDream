<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Listeners;

use Module\Auth\Domain\Events\AccountCanceled;
use Module\Chat\Domain\Model\ChatHistoryModel;
use Module\Chat\Domain\Model\MessageModel;

final class AccountCanceledListener {

    public function __construct(AccountCanceled $event) {
        ChatHistoryModel::where('user_id', $event->getUserId())->delete();
        MessageModel::where('user_id', $event->getUserId())->delete();
    }
}
