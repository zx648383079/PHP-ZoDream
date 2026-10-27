<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Listeners;

use Module\Auth\Domain\Advanced\Entities\ApplyLogEntity;
use Module\Auth\Domain\Advanced\Entities\UserEquityEntity;
use Module\Auth\Domain\Advanced\Entities\UserRelationshipEntity;
use Module\Auth\Domain\Events\CancelAccount;
use Module\Auth\Domain\Entities\ActionLogEntity;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Entities\InviteCodeEntity;
use Module\Auth\Domain\Entities\InviteLogEntity;
use Module\Auth\Domain\Entities\LoginLogEntity;
use Module\Auth\Domain\Entities\OAuthEntity;
use Module\Auth\Domain\Model\UserMetaModel;

class CancelAccountListener {

    public function __construct(CancelAccount $event) {
        BulletinUserModel::where('user_id', $event->getUserId())->delete();
        OAuthEntity::where('user_id', $event->getUserId())->delete();
        UserMetaModel::where('user_id', $event->getUserId())->delete();
        ActionLogEntity::where('user_id', $event->getUserId())->delete();
        LoginLogEntity::where('user_id', $event->getUserId())->delete();
        InviteCodeEntity::where('user_id', $event->getUserId())->delete();
        InviteLogEntity::where('user_id', $event->getUserId())->delete();
        if (AUTH_ADVANCED) {
            ApplyLogEntity::where('user_id', $event->getUserId())->delete();
            UserEquityEntity::where('user_id', $event->getUserId())->delete();
            UserRelationshipEntity::where('user_id', $event->getUserId())->orWhere('link_id', $event->getUserId())->delete();
        }
    }
}
