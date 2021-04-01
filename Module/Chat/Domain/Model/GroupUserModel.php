<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class GroupUserModel
 * @package Module\Chat\Domain\Model
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property string $name
 * @property integer $role_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class GroupUserModel extends Model {
    public static function tableName() {
        return 'chat_group_user';
    }

    protected function rules() {
        return [
            'group_id' => 'required|int',
            'user_id' => 'required|int',
            'name' => 'string:0,50',
            'role_id' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'group_id' => 'Group Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'role_id' => 'Role Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}