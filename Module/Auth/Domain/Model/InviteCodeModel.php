<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 *
 * 邀请码
 * @property integer $id
 * @property integer $user_id
 * @property string $code
 * @property integer $amount
 * @property integer $invite_count
 * @property integer $expired_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class InviteCodeModel extends Model {

    public static function tableName() {
        return 'user_invite_code';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'code' => 'required|string:0,6',
            'amount' => 'int',
            'invite_count' => 'int',
            'expired_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'code' => 'Code',
            'amount' => 'Amount',
            'expired_at' => 'Expired At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}