<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;

/**
 * Class GroupUserModel
 * @package Module\Chat\Domain\Model
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property string $name
 * @property integer $role_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class GroupUserModel extends Model {
    public static function tableName() {
        return 'chat_group_user';
    }

    protected function rules() {
        return [
            'group_id' => 'required|int',
            'user_id' => 'required|int',
            'name' => 'required|string:0,100',
            'role_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'group_id' => 'Group Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'role_id' => 'Role Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}