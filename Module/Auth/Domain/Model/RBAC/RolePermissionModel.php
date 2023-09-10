<?php
namespace Module\Auth\Domain\Model\RBAC;


use Domain\Model\Model;

/**
 * Class RolePermissionModel
 * @package Module\Auth\Domain\Model
 * @property integer $role_id
 * @property integer $permission_id
 */
class RolePermissionModel extends Model {

    protected string $primaryKey = '';

    public static function tableName(): string {
        return 'rbac_role_permission';
    }

    protected function rules(): array {
        return [
            'role_id' => 'required|int',
            'permission_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'role_id' => 'Role Id',
            'permission_id' => 'Permission Id',
        ];
    }
}