<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class RolePermissionModel
 * @package Module\Auth\Domain\Model
 * @property integer $role_id
 * @property integer $permission_id
 */
class RolePermissionModel extends Model {

    protected $primaryKey = false;

    public static function tableName() {
        return 'role_permission';
    }

    protected function rules() {
        return [
            'role_id' => 'required|int',
            'permission_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'role_id' => 'Role Id',
            'permission_id' => 'Permission Id',
        ];
    }
}