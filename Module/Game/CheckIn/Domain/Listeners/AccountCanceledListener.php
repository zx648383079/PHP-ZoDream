<?php
namespace Module\Game\CheckIn\Domain\Listeners;


use Module\Auth\Domain\Events\AccountCanceled;
use Module\Game\CheckIn\Domain\Model\CheckInModel;

final class AccountCanceledListener {

    public function __construct(AccountCanceled $event) {
        CheckInModel::where('user_id', $event->getUserId())->delete();
    }
}
