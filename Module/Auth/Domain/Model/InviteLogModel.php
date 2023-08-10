<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;
use Module\Auth\Domain\Entities\InviteLogEntity;

/**
 * 邀请记录
 * @property integer $id
 * @property integer $user_id
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $code_id
 * @property integer $status
 * @property integer $updated_at
 */
class InviteLogModel extends InviteLogEntity {
    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function inviter() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'parent_id');
    }
}