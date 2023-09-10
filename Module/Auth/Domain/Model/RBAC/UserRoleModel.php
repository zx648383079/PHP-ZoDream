<?php
namespace Module\Auth\Domain\Model\RBAC;


use Domain\Model\Model;

/**
 * Class UserRoleModel
 * @package Module\Auth\Domain\Model
 * @property integer $user_id
 * @property integer $role_id
 */
class UserRoleModel extends Model {

    protected string $primaryKey = '';

    public static function tableName(): string {
        return 'rbac_user_role';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'role_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'user_id' => 'User Id',
            'role_id' => 'Role Id',
        ];
    }
}