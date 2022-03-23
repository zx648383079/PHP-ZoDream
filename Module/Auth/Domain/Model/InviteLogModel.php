<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * 邀请记录
 * @property integer $id
 * @property integer $user_id
 * @property integer $parent_id
 * @property string $code
 * @property integer $created_at
 */
class InviteLogModel extends Model {

    public static function tableName() {
        return 'user_invite_log';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'parent_id' => 'int',
            'code' => 'string:0,20',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'parent_id' => 'Parent Id',
            'code' => 'Code',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function inviter() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'parent_id');
    }
}