<?php
namespace Module\Game\CheckIn\Domain\Listeners;


use Module\Auth\Domain\Events\CancelAccount;
use Module\Game\CheckIn\Domain\Model\CheckInModel;

class CancelAccountListener {

    public function __construct(CancelAccount $event) {
        CheckInModel::where('user_id', $event->getUserId())->delete();
    }
}
