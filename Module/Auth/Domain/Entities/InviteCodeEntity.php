<?php
namespace Module\Auth\Domain\Entities;


use Domain\Entities\Entity;
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
class InviteCodeEntity extends Entity {
    public static function tableName(): string {
        return 'user_invite_code';
    }


    protected function rules(): array {
        return [
            'user_id' => 'int',
            'amount' => 'int',
            'invite_count' => 'int',
            'expired_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
            'type' => 'int:0,127',
            'token' => 'required|string:0,32',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'amount' => 'Amount',
            'invite_count' => 'Invite Count',
            'expired_at' => 'Expired At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'type' => 'Type',
            'token' => 'Token',
        ];
    }
}