<?php
namespace Module\Task\Domain\Listeners;


use Module\Auth\Domain\Events\AccountCanceled;
use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;

final class AccountCanceledListener {

    public function __construct(AccountCanceled $event) {
        TaskModel::where('user_id', $event->getUserId())->delete();
        TaskDayModel::where('user_id', $event->getUserId())->delete();
        TaskLogModel::where('user_id', $event->getUserId())->delete();
    }
}
