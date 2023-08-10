<?php
namespace Module\Auth\Domain\Model;

use Module\Auth\Domain\Entities\InviteCodeEntity;

/**
 *
 * 邀请码
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property integer $invite_count
 * @property integer $expired_at
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $type
 * @property string $token
 */
class InviteCodeModel extends InviteCodeEntity {

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}