<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class UserRoleModel
 * @package Module\Auth\Domain\Model
 * @property integer $user_id
 * @property integer $role_id
 */
class UserRoleModel extends Model {

    protected $primaryKey = false;

    public static function tableName() {
        return 'user_role';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'role_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'user_id' => 'User Id',
            'role_id' => 'Role Id',
        ];
    }
}