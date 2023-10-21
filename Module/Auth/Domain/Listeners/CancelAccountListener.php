<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Listeners;

use Module\Auth\Domain\Events\CancelAccount;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Model\InviteCodeModel;
use Module\Auth\Domain\Model\InviteLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\MailLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserMetaModel;

class CancelAccountListener {

    public function __construct(CancelAccount $event) {
        BulletinUserModel::where('user_id', $event->getUserId())->delete();
        OAuthModel::where('user_id', $event->getUserId())->delete();
        UserMetaModel::where('user_id', $event->getUserId())->delete();
        AccountLogModel::where('user_id', $event->getUserId())->delete();
        ActionLogModel::where('user_id', $event->getUserId())->delete();
        LoginLogModel::where('user_id', $event->getUserId())->delete();
        InviteCodeModel::where('user_id', $event->getUserId())->delete();
        InviteLogModel::where('user_id', $event->getUserId())->delete();
    }
}
